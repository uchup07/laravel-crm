<?php

namespace VentureDrake\LaravelCrm\Http\Livewire;

use Livewire\Component;
use VentureDrake\LaravelCrm\Traits\NotifyToast;

class LiveOrderItems extends Component
{
    use NotifyToast;

    public $order;

    public $products;

    public $order_product_id;

    public $product_id;

    public $name;

    public $unit_price;

    public $quantity;

    public $amount;

    public $inputs = [];

    public $i = 0;

    public $sub_total = 0;

    public $discount = 0;

    public $tax = 0;

    public $adjustment = 0;

    public $total = 0;

    protected $listeners = ['loadItemDefault'];

    public function mount($order, $products, $old = null)
    {
        $this->order = $order;
        $this->products = $products;
        $this->old = $old;

        if ($this->old) {
            foreach ($this->old as $old) {
                $this->add($this->i);
                $this->order_product_id[$this->i] = $old['order_product_id'] ?? null;
                $this->product_id[$this->i] = $old['product_id'] ?? null;
                $this->name[$this->i] = $old['name'] ?? null;
                $this->quantity[$this->i] = $old['quantity'] ?? null;
                $this->unit_price[$this->i] = $old['unit_price'] ?? null;
                $this->amount[$this->i] = $old['amount'] ?? null;
            }
        } elseif ($this->products && $this->products->count() > 0) {
            foreach ($this->products as $orderProduct) {
                $this->add($this->i);
                $this->order_product_id[$this->i] = $orderProduct->id;
                $this->product_id[$this->i] = $orderProduct->product->id ?? null;
                $this->name[$this->i] = $orderProduct->product->name ?? null;
                $this->quantity[$this->i] = $orderProduct->quantity;
                $this->unit_price[$this->i] = $orderProduct->price / 100;
                $this->amount[$this->i] = $orderProduct->amount / 100;
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
        $this->unit_price[$i] = null;
        $this->quantity[$i] = null;
        array_push($this->inputs, $i);
    }

    public function loadItemDefault($id)
    {
        $product = \VentureDrake\LaravelCrm\Models\Product::find($this->product_id[$id]);
        $this->unit_price[$id] = ($product->getDefaultPrice()->unit_price / 100);
        $this->quantity[$id] = 1;
        $this->calculateAmounts();
    }

    public function calculateAmounts()
    {
        $this->sub_total = 0;
        $this->tax = 0;
        $this->total = 0;

        for ($i = 1; $i <= $this->i; $i++) {
            $this->amount[$i] = $this->unit_price[$i] * $this->quantity[$i];
            $this->sub_total += $this->amount[$i];

            if (isset($this->product_id[$i]) && $product = \VentureDrake\LaravelCrm\Models\Product::find($this->product_id[$i])) {
                $this->tax += $this->amount[$i] * ($product->tax_rate / 100);
            }
        }

        $this->total = $this->sub_total + $this->tax;
    }

    public function render()
    {
        return view('laravel-crm::livewire.order-items');
    }
}
