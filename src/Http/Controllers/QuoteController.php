<?php

namespace VentureDrake\LaravelCrm\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use VentureDrake\LaravelCrm\Http\Requests\StoreQuoteRequest;
use VentureDrake\LaravelCrm\Http\Requests\UpdateQuoteRequest;
use VentureDrake\LaravelCrm\Models\Organisation;
use VentureDrake\LaravelCrm\Models\Person;
use VentureDrake\LaravelCrm\Models\Quote;
use VentureDrake\LaravelCrm\Services\OrderService;
use VentureDrake\LaravelCrm\Services\OrganisationService;
use VentureDrake\LaravelCrm\Services\PersonService;
use VentureDrake\LaravelCrm\Services\QuoteService;
use VentureDrake\LaravelCrm\Services\SettingService;

class QuoteController extends Controller
{
    /**
     * @var QuoteService
     */
    private $quoteService;

    /**
     * @var PersonService
     */
    private $personService;

    /**
     * @var OrganisationService
     */
    private $organisationService;

    /**
     * @var OrderService
     */
    private $orderService;

    /**
     * @var SettingService
     */
    private $settingService;

    public function __construct(QuoteService $quoteService, PersonService $personService, OrganisationService $organisationService, OrderService $orderService, SettingService $settingService)
    {
        $this->quoteService = $quoteService;
        $this->personService = $personService;
        $this->organisationService = $organisationService;
        $this->orderService = $orderService;
        $this->settingService = $settingService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Quote::resetSearchValue($request);
        $params = Quote::filters($request);

        if (Quote::filter($params)->get()->count() < 30) {
            $quotes = Quote::filter($params)->latest()->get();
        } else {
            $quotes = Quote::filter($params)->latest()->paginate(30);
        }

        return view('laravel-crm::quotes.index', [
            'quotes' => $quotes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        switch ($request->model) {
            case "person":
                $person = Person::find($request->id);

                break;

            case "organisation":
                $organisation = Organisation::find($request->id);

                break;
        }

        return view('laravel-crm::quotes.create', [
            'person' => $person ?? null,
            'organisation' => $organisation ?? null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreQuoteRequest $request)
    {
        if ($request->person_name && ! $request->person_id) {
            $person = $this->personService->createFromRelated($request);
        } elseif ($request->person_id) {
            $person = Person::find($request->person_id);
        }

        if ($request->organisation_name && ! $request->organisation_id) {
            $organisation = $this->organisationService->createFromRelated($request);
        } elseif ($request->organisation_id) {
            $organisation = Organisation::find($request->organisation_id);
        }

        $this->quoteService->create($request, $person ?? null, $organisation ?? null);

        flash(ucfirst(trans('laravel-crm::lang.quote_stored')))->success()->important();

        return redirect(route('laravel-crm.quotes.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Quote $quote)
    {
        if ($quote->person) {
            $email = $quote->person->getPrimaryEmail();
            $phone = $quote->person->getPrimaryPhone();
            $address = $quote->person->getPrimaryAddress();
        }

        if ($quote->organisation) {
            $organisation_address = $quote->organisation->getPrimaryAddress();
        }

        return view('laravel-crm::quotes.show', [
            'quote' => $quote,
            'email' => $email ?? null,
            'phone' => $phone ?? null,
            'address' => $address ?? null,
            'organisation_address' => $organisation_address ?? null,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Quote $quote)
    {
        if ($quote->person) {
            $email = $quote->person->getPrimaryEmail();
            $phone = $quote->person->getPrimaryPhone();
        }

        if ($quote->organisation) {
            $address = $quote->organisation->getPrimaryAddress();
        }

        return view('laravel-crm::quotes.edit', [
            'quote' => $quote,
            'email' => $email ?? null,
            'phone' => $phone ?? null,
            'address' => $address ?? null,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQuoteRequest $request, Quote $quote)
    {
        if ($request->person_name && ! $request->person_id) {
            $person = $this->personService->createFromRelated($request);
        } elseif ($request->person_id) {
            $person = Person::find($request->person_id);
        }

        if ($request->organisation_name && ! $request->organisation_id) {
            $organisation = $this->organisationService->createFromRelated($request);
        } elseif ($request->organisation_id) {
            $organisation = Organisation::find($request->organisation_id);
        }

        $quote = $this->quoteService->update($request, $quote, $person ?? null, $organisation ?? null);

        flash(ucfirst(trans('laravel-crm::lang.quote_updated')))->success()->important();

        return redirect(route('laravel-crm.quotes.show', $quote));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quote $quote)
    {
        $quote->delete();

        flash(ucfirst(trans('laravel-crm::lang.quote_deleted')))->success()->important();

        return redirect(route('laravel-crm.quotes.index'));
    }

    public function search(Request $request)
    {
        $searchValue = Quote::searchValue($request);

        if (! $searchValue || trim($searchValue) == '') {
            return redirect(route('laravel-crm.quotes.index'));
        }

        $params = Quote::filters($request, 'search');

        $quotes = Quote::filter($params)
            ->select(
                config('laravel-crm.db_table_prefix').'quotes.*',
                config('laravel-crm.db_table_prefix').'people.first_name',
                config('laravel-crm.db_table_prefix').'people.middle_name',
                config('laravel-crm.db_table_prefix').'people.last_name',
                config('laravel-crm.db_table_prefix').'people.maiden_name',
                config('laravel-crm.db_table_prefix').'organisations.name'
            )
            ->leftJoin(config('laravel-crm.db_table_prefix').'people', config('laravel-crm.db_table_prefix').'quotes.person_id', '=', config('laravel-crm.db_table_prefix').'people.id')
            ->leftJoin(config('laravel-crm.db_table_prefix').'organisations', config('laravel-crm.db_table_prefix').'quotes.organisation_id', '=', config('laravel-crm.db_table_prefix').'organisations.id')
            ->get()
            ->filter(function ($record) use ($searchValue) {
                foreach ($record->getSearchable() as $field) {
                    if (Str::contains($field, '.')) {
                        $field = explode('.', $field);
                        if ($record->{$field[1]} && $descryptedField = decrypt($record->{$field[1]})) {
                            if (Str::contains(strtolower($descryptedField), strtolower($searchValue))) {
                                return $record;
                            }
                        }
                    } elseif ($record->{$field}) {
                        if (Str::contains(strtolower($record->{$field}), strtolower($searchValue))) {
                            return $record;
                        }
                    }
                }
            });

        return view('laravel-crm::quotes.index', [
            'quotes' => $quotes,
            'searchValue' => $searchValue ?? null,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function accept(Quote $quote)
    {
        $quote->update([
            'accepted_at' => Carbon::now(),
        ]);

        flash(ucfirst(trans('laravel-crm::lang.quote_accepted')))->success()->important();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject(Quote $quote)
    {
        $quote->update([
            'rejected_at' => Carbon::now(),
        ]);

        flash(ucfirst(trans('laravel-crm::lang.quote_rejected')))->success()->important();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unaccept(Quote $quote)
    {
        $quote->update([
            'accepted_at' => null,
        ]);

        flash(ucfirst(trans('laravel-crm::lang.quote_unaccepted')))->success()->important();

        return back();
    }

    /**
     * Create an order from the quote
     *
     * @param  Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function createOrder(Quote $quote)
    {
        $request = new \Illuminate\Http\Request();
        $products = [];

        foreach ($quote->quoteProducts as $quoteProduct) {
            $products[] = [
                'product_id' => $quoteProduct->product_id,
                'quantity' => $quoteProduct->quantity,
                'unit_price' => $quoteProduct->unit_price / 100,
                'amount' => $quoteProduct->amount / 100,
                'currency' => $quoteProduct->currency,
            ];
        }

        $request->replace([
            'lead_id' => $quote->lead_id ?? null,
            'deal_id' => $quote->deal_id ?? null,
            'quote_id' => $quote->id,
            'person_id' => $quote->person->id ?? null,
            'organisation_id' => $quote->organisation->id ?? null,
            'description' => $quote->description,
            'reference' => $quote->reference,
            'currency' => $quote->currency,
            'subtotal' => $quote->sub_total / 100,
            'discount' => $quote->discount / 100,
            'tax' => $quote->tax / 100,
            'adjustments' => $quote->adjustment / 100,
            'total' => $quote->total / 100,
            'user_owner_id' => $quote->user_owner_id,
            'products' => $products,
        ]);

        $this->orderService->create($request, $quote->person ?? null, $quote ?? null);

        flash(ucfirst(trans('laravel-crm::lang.order_created_from_quote')))->success()->important();

        return back();
    }

    public function download(Quote $quote)
    {
        if ($quote->person) {
            $email = $quote->person->getPrimaryEmail();
            $phone = $quote->person->getPrimaryPhone();
            $address = $quote->person->getPrimaryAddress();
        }

        if ($quote->organisation) {
            $organisation_address = $quote->organisation->getPrimaryAddress();
        }
        
        /*$pdfLocation = 'laravel-crm/'.strtolower(class_basename($quote)).'/'.$quote->id.'/';

        if(!File::exists($pdfLocation)){
            Storage::makeDirectory($pdfLocation);
        }*/
        
        /*return view('laravel-crm::quotes.partials.document', [
            'quote' => $quote,
            'email' => $email ?? null,
            'phone' => $phone ?? null,
            'address' => $address ?? null,
            'organisation_address' => $organisation_address ?? null,
            'fromName' => $this->settingService->get('organisation_name')->value ?? null,
            'logo' => $this->settingService->get('logo_file')->value ?? null,
        ]);*/
        
        return Pdf::setOption([
            'fontDir' => public_path('vendor/laravel-crm/fonts'),
        ])
            ->loadView('laravel-crm::quotes.partials.document', [
            'quote' => $quote,
            'email' => $email ?? null,
            'phone' => $phone ?? null,
            'address' => $address ?? null,
            'organisation_address' => $organisation_address ?? null,
            'fromName' => $this->settingService->get('organisation_name')->value ?? null,
            'logo' => $this->settingService->get('logo_file')->value ?? null,
        ])->download('quote-'.$quote->id.'.pdf');
    }
}
