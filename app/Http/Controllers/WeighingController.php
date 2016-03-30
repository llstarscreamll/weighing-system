<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Weighing;
use Illuminate\Http\Request;
use App\Helpers\SerialPortHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\WeighingRequest;

class WeighingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // las opciones del puerto
        $data['portSetting'] = \App\Models\Setting::where('key', 'weighing.weight-port-listener')->first();

        // la clase que gestina la conexión con el puerto serial
        $serialPort = new SerialPortHelper([$data['portSetting']->value, 9600, 8, 1]);

        // la info del peso que arroja el puerto serial
        $data['weight'] = '';

        // si se puede establecer conexión con el puerto serial
        if ($serialPort->isTheExtentionInstaled() && $serialPort->conectToSerialPort()){

            $data['weight'] = trim($serialPort->readData());
            $serialPort->closeConexion();
            
        }else{
            $request->session()->flash('error', 'No se puede establecer conexión con el puerto serial.');
        }

        $data['employees'] = \App\Models\Employee::all()->lists('fullname', 'id')->toArray();

        $data['weighings'] = Weighing::orderBy('created_at', 'desc')->paginate(20);

        return view('scales.registerWeight', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WeighingRequest $request)
    {
        $weighing = Weighing::create($request->all());
        $weighing->save()
            ? $request->session()->flash('success', 'Peso registrado correctamente.')
            : $request->session()->flash('error', 'Ocurrió un error guardando la información de pesaje.');

        return redirect()->route('weighing.printTicket', $weighing->id);
    }

    /**
     * Muestra una vista para imprimir el ticket del pesaje del producto.
     * @param  int  $id
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function printTicket($id, Request $request)
    {
        $data['weighing'] = Weighing::findOrFail($id);

        return view('scales.printTicket', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
