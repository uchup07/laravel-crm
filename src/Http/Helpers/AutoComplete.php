<?php

namespace VentureDrake\LaravelCrm\Http\Helpers\AutoComplete;

use VentureDrake\LaravelCrm\Models\Client;
use VentureDrake\LaravelCrm\Models\Organisation;
use VentureDrake\LaravelCrm\Models\Person;
use VentureDrake\LaravelCrm\Models\Product;

function clients()
{
    $data = [];

    foreach (Client::all() as $client) {
        $data[$client->name] = $client->id;
    }

    return json_encode($data);
}

function people($organisationId = null)
{
    $data = [];

    if(is_null($organisationId)) {
        $peoples = (auth()->user()->hasRole(['Admin','Owner','Manager'])) ? Person::all() : Person::where('user_owner_id', auth()->user()->id)->get();
    } else {
        $organisation = Organisation::find($organisationId);
        if($organisation) {
            $contacts = $organisation->contacts()->where('entityable_type', 'LIKE', '%Person%')->get();
            $peoples = $contacts->map(function($item, $key) {
                $item->id = $item->entityable->id;
                $item->name = $item->entityable->name;
                return $item;
            });

        }
    }
        
    foreach ($peoples as $person) {
        $data[$person->name] = $person->id;
    }
    
    return json_encode($data);
}

function organisations()
{
    $data = [];

    $organisations = (auth()->user()->hasRole(['Admin','Owner','Manager'])) ? Organisation::all() : Organisation::where('user_owner_id', auth()->user()->id)->get();

    foreach ($organisations as $organisation) {
        $data[$organisation->name] = $organisation->id;
    }

    return json_encode($data);
}

function products()
{
    $data = [];

    foreach (Product::all() as $product) {
        $data[$product->name] = $product->id;
    }

    return json_encode($data);
}

function productsSelect2()
{
    $data = [];

    /*$data[] = [
        'id' => -1,
        'text' => null,
    ];*/
    
    foreach (Product::all() as $product) {
        $data[] = [
            'id' => $product->id,
            'text' => $product->name,
        ];
    }

    return json_encode($data);
}
