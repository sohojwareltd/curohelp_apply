<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WorkerInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InvoiceUploadController extends Controller
{
    /**
     * Handle invoice file upload from portal domain
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:pdf|max:10240',
            'worker_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $file = $request->file('file');
            $workerId = $request->input('worker_id');
            
            // Generate a unique filename while preserving the original name
            $originalName = $file->getClientOriginalName();
            $filename = time() . '_' . $workerId . '_' . $originalName;
            
            // Store the file in storage/app/public/invoices/workers
            $path = Storage::disk('public')->putFileAs(
                'invoices/workers',
                $file,
                $filename
            );
            
            // Create or update worker invoice record
            $invoice = WorkerInvoice::updateOrCreate(
                [
                    'worker_id' => $workerId,
                    'file_path' => $path,
                ],
                [
                    'file_name' => $originalName,
                    'file_size' => $file->getSize(),
                    'uploaded_at' => now(),
                ]
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Invoice uploaded successfully',
                'file_url' => Storage::url($path),
                'data' => [
                    'id' => $invoice->id,
                    'worker_id' => $invoice->worker_id,
                    'file_name' => $invoice->file_name,
                    'file_path' => $path,
                    'uploaded_at' => $invoice->uploaded_at,
                ]
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload invoice',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}