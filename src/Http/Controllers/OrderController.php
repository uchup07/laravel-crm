<?php

namespace VentureDrake\LaravelCrm\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use VentureDrake\LaravelCrm\Http\Requests\StoreOrderRequest;
use VentureDrake\LaravelCrm\Http\Requests\UpdateOrderRequest;
use VentureDrake\LaravelCrm\Models\Order;
use VentureDrake\LaravelCrm\Models\Organisation;
use VentureDrake\LaravelCrm\Models\Person;
use VentureDrake\LaravelCrm\Services\InvoiceService;
use VentureDrake\LaravelCrm\Services\OrderService;
use VentureDrake\LaravelCrm\Services\OrganisationService;
use VentureDrake\LaravelCrm\Services\PersonService;

class OrderController extends Controller
{
    /**
     * @var OrderService
     */
    private $orderService;

    /**
     * @var PersonService
     */
    private $personService;

    /**
     * @var OrganisationService
     */
    private $organisationService;

    /**
     * @var InvoiceService
     */
    private $invoiceService;

    public function __construct(OrderService $orderService, PersonService $personService, OrganisationService $organisationService, InvoiceService $invoiceService)
    {
        $this->orderService = $orderService;
        $this->personService = $personService;
        $this->organisationService = $organisationService;
        $this->invoiceService = $invoiceService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Order::resetSearchValue($request);
        $params = Order::filters($request);

        if (Order::filter($params)->get()->count() < 30) {
            $orders = Order::filter($params)->latest()->get();
        } else {
            $orders = Order::filter($params)->latest()->paginate(30);
        }

        return view('laravel-crm::orders.index', [
            'orders' => $orders,
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

        return view('laravel-crm::orders.create', [
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
    public function store(StoreOrderRequest $request)
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

        $this->orderService->create($request, $person ?? null, $organisation ?? null);

        flash(ucfirst(trans('laravel-crm::lang.order_stored')))->success()->important();

        return redirect(route('laravel-crm.orders.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        if ($order->person) {
            $email = $order->person->getPrimaryEmail();
            $phone = $order->person->getPrimaryPhone();
            $address = $order->person->getPrimaryAddress();
        }

        if ($order->organisation) {
            $organisation_address = $order->organisation->getPrimaryAddress();
        }

        return view('laravel-crm::orders.show', [
            'order' => $order,
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
    public function edit(Order $order)
    {
        if ($order->person) {
            $email = $order->person->getPrimaryEmail();
            $phone = $order->person->getPrimaryPhone();
        }

        if ($order->organisation) {
            $address = $order->organisation->getPrimaryAddress();
        }

        return view('laravel-crm::orders.edit', [
            'order' => $order,
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
    public function update(UpdateOrderRequest $request, Order $order)
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

        $order = $this->orderService->update($request, $order, $person ?? null, $organisation ?? null);

        flash(ucfirst(trans('laravel-crm::lang.order_updated')))->success()->important();

        return redirect(route('laravel-crm.orders.show', $order));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();

        flash(ucfirst(trans('laravel-crm::lang.order_deleted')))->success()->important();

        return redirect(route('laravel-crm.orders.index'));
    }

    public function search(Request $request)
    {
        $searchValue = Order::searchValue($request);

        if (! $searchValue || trim($searchValue) == '') {
            return redirect(route('laravel-crm.orders.index'));
        }

        $params = Order::filters($request, 'search');

        $orders = Order::filter($params)
            ->select(
                config('laravel-crm.db_table_prefix').'orders.*',
                config('laravel-crm.db_table_prefix').'people.first_name',
                config('laravel-crm.db_table_prefix').'people.middle_name',
                config('laravel-crm.db_table_prefix').'people.last_name',
                config('laravel-crm.db_table_prefix').'people.maiden_name',
                config('laravel-crm.db_table_prefix').'organisations.name'
            )
            ->leftJoin(config('laravel-crm.db_table_prefix').'people', config('laravel-crm.db_table_prefix').'orders.person_id', '=', config('laravel-crm.db_table_prefix').'people.id')
            ->leftJoin(config('laravel-crm.db_table_prefix').'organisations', config('laravel-crm.db_table_prefix').'orders.organisation_id', '=', config('laravel-crm.db_table_prefix').'organisations.id')
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

        return view('laravel-crm::orders.index', [
            'orders' => $orders,
            'searchValue' => $searchValue ?? null,
        ]);
    }
}
