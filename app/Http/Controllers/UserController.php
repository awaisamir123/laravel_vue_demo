<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $user;

    /**
     * UserController constructor.
     * @param $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $items = $this->user->all();
        return response()->json(['users' => $items, 'currentDate' => date('d/m/Y')]);
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


    public function store(Request $request)
    {
        if($request->id == 0)
        {
            $item = new User();
            $message = 'Successfully Added';
        }
        else
        {
            $item = User::find($request->id);
            if(!$item)
            {
                return response()->json(['status' => '0' ,'message' => 'User not found.']);
            }
            $message = 'Successfully Updated';
        }
            $item->name = $request->get('name');
            $item->email = $request->get('email');
            $item->password = Hash::make('12345');

        if($item->save())
        {
            return response()->json(['status' => '1' , 'message' => $message]);
        }
        else
        {
            return response()->json(['status' => '0' ,'message' => 'Something went wrong! Try again.']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = $this->user->find($id);

        if($item)
        {
            return response()->json(['status' => '1' , 'message' => '', 'item' => $item]);
        }
        else
        {
            return response()->json(['status' => '0' ,'message' => 'User not found', 'item' => '']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Item::find($id);
        return response()->json($item);
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, array(
            'name' => 'required|string',
            'price' => 'required|numeric',
        ));
        $item = Item::find($id);
        $item->name = $request->get('name');
        $item->price = $request->get('price');
        $item->save();

        return response()->json('Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = $this->user->find($id);
        $item->delete();

        return response()->json('Successfully Deleted');
    }
}
