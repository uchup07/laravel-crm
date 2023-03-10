<?php

namespace VentureDrake\LaravelCrm\Http\Livewire;

use Livewire\Component;
use VentureDrake\LaravelCrm\Traits\NotifyToast;

class LiveInvoiceLines extends Component
{
    use NotifyToast;

    public $invoice;

    public $invoiceLines;

    public $invoice_line_id;

    public $product_id;

    public $name;

    public $price;

    public $quantity;

    public $amount;

    public $inputs = [];

    public $i = 0;

    public $sub_total = 0;

    public $tax = 0;

    public $total = 0;

    protected $listeners = ['loadInvoiceLineDefault'];

    public function mount($invoice, $invoiceLines, $old = null)
    {
        $this->invoice = $invoice;
        $this->invoiceLines = $invoiceLines;
        $this->old = $old;

        if ($this->old) {
            foreach ($this->old as $old) {
                $this->add($this->i);
                $this->invoice_product_id[$this->i] = $old['invoice_product_id'] ?? null;
                $this->product_id[$this->i] = $old['product_id'] ?? null;
                $this->name[$this->i] = $old['name'] ?? null;
                $this->quantity[$this->i] = $old['quantity'] ?? null;
                $this->price[$this->i] = $old['price'] ?? null;
                $this->amount[$this->i] = $old['amount'] ?? null;
            }
        } elseif ($this->invoiceLines && $this->invoiceLines->count() > 0) {
            foreach ($this->invoiceLines as $invoiceLine) {
                $this->add($this->i);
                $this->invoice_line_id[$this->i] = $invoiceLine->id;
                $this->product_id[$this->i] = $invoiceLine->product->id ?? null;
                $this->name[$this->i] = $invoiceLine->product->name ?? null;
                $this->quantity[$this->i] = $invoiceLine->quantity;
                $this->price[$this->i] = $invoiceLine->price / 100;
                $this->amount[$this->i] = $invoiceLine->amount / 100;
            }
        } else {
            $this->add($this->i);
        }

        $this->calculateAmounts();
    }

    public function add($i)
    {
        $i = $i + 1;
        $this->i = $i;
        $this->price[$i] = null;
        $this->quantity[$i] = null;
        array_push($this->inputs, $i);
    }

    public function loadInvoiceLineDefault($id)
    {
        $product = \VentureDrake\LaravelCrm\Models\Product::find($this->product_id[$id]);
        $this->price[$id] = ($product->getDefaultPrice()->unit_price / 100);
        $this->quantity[$id] = 1;
        $this->calculateAmounts();
    }

    public function calculateAmounts()
    {
        $this->sub_total = 0;
        $this->tax = 0;
        $this->total = 0;

        for ($i = 1; $i <= $this->i; $i++) {
            $this->amount[$i] = $this->price[$i] * $this->quantity[$i];
            $this->sub_total += $this->amount[$i];

            if (isset($this->product_id[$i]) && $product = \VentureDrake\LaravelCrm\Models\Product::find($this->product_id[$i])) {
                $this->tax += $this->amount[$i] * ($product->tax_rate / 100);
            }
        }

        $this->total = $this->sub_total + $this->tax;
    }

    public function render()
    {
        return view('laravel-crm::livewire.invoice-lines');
    }
}
