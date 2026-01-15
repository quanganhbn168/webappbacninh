<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ToolController extends Controller
{
    /**
     * QR Code Generator Tool
     */
    public function qrCode()
    {
        return view('tools.qr-code');
    }

    /**
     * Bank QR Code (VietQR)
     */
    public function bankQr()
    {
        $banks = config('vietqr_banks.banks');
        return view('tools.bank-qr', compact('banks'));
    }

    /**
     * Perpetual Calendar Tool (Lịch Vạn Niên)
     */
    public function calendar()
    {
        return view('tools.calendar');
    }

    /**
     * Vòng Quay Bần Hàn (Food Wheel)
     */
    public function foodWheel()
    {
        return view('tools.food-wheel');
    }
}
