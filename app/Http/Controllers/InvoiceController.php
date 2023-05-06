<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function find_invoice(Request $request)
    {
        $invoice_ref = $request->input('invoice_ref');
        $invoice = Invoice::where('invoice_ref', $invoice_ref)->first();
        if($invoice){
            return view('invoice', ['invoice' => $invoice]);
        }else{
            return view('index', ['error' => 'Sorry!, Invoice does not exits.']);
        }
    }

    public function pay_invoice(Request $request)
    {
        $invoice_ref = $request->input('invoice_ref');
        $invoice = Invoice::where('invoice_ref', $invoice_ref)->first();
        if($invoice){
            $invoice->status = "PAID";
            $invoice->save();
            return view('invoice', ['invoice' => $invoice]);
        }else{
            return view('index', ['error' => 'Sorry!, Invoice does not exits.']);
        }
    }

    public function generate_invoice($student_id, $amount){
        $invoice = new Invoice;
        $invoice_ref = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10/strlen($x)) )),1,10);
        $invoice->invoice_ref = $invoice_ref;
        $invoice->student_id = $student_id;
        $invoice->amount = $amount;
        $invoice->status = "UNPAID";
        $invoice->save();

        return response()->json(['message' => 'Invoice generated successfully','invoice' => $invoice], 201);
    }

    public function check_invoices($student_id){
        $invoices = Invoice::where('student_id', $student_id)->get();
        if($invoices){
            return response()->json(['message' => 'Invoices found','invoices' => $invoices], 200);
        }else{
            return response()->json(['message' => 'Sorry!, Invoice does not exits.','invoices' => $invoices], 404);
        }
    }
}
