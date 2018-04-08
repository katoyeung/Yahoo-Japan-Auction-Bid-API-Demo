<?php

namespace App\Http\Controllers;

use App\Traits\YahooAPI;
use Illuminate\Http\Request;

class BidController extends Controller
{
    use YahooAPI;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'keyword' => 'required'
        ]);

        $items = null;
        if (preg_match('/(?<auctionID>[a-z]{1}[0-9]{9})/is', $request->keyword, $match)) {
            return redirect()->route('bid.show', $match['auctionID']);
        } elseif (preg_match('/^(?<category>[0-9]+)$/is', $request->keyword, $match)) {
            $request = $this->getCategory($match['category']);
        } else {
            $query = [
                'query' => $request->keyword,
                'sort' => 'cbids',
                'order' => 'a'
            ];
            $request = $this->search($query);
        }

        if (isset($request->ResultSet->Result->Item)) {
            $items = $request->ResultSet->Result->Item;
        }

        return view('welcome', ['type' => 'list', 'items' => $items]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'itemId' => 'required',
            'bid' => 'required|integer|between:11,100'
        ]);

        $query = [
            'itemId' => $request->itemId,
            'quantity' => 1,
            'type' => 'bid',
            'bid' => $request->bid
        ];
        $result = $this->bid($query);

        if ($result && $result->status == 'success') {
            return redirect()->back()->with('status', 'Bid successfully.');
        } else {
            return redirect()->back()->with('error', $result->message);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = null;
        if ($id) {
            $item = $this->getItem($id);
            if (isset($item->ResultSet->Result)) {
                $item = $item->ResultSet->Result;
            }
        }

        return view('welcome', ['type' => 'item', 'item' => $item]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
