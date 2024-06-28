<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileDownloadController extends Controller
{
    public function download($type, $filename, $delete = false){
        if ($type === 'sample_campaign_file') {
            return $this->downloadSampleCampaignFile($filename);
        }
        $filePath = 'uploads/' . $type . '/' . $filename;
        if (Storage::exists($filePath)) {
            return Storage::download($filePath);
        } else {
            return redirect()->back()->with('error', 'File not found.');
        }
    }

    private function downloadSampleCampaignFile($filename){
        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Name', 'Email', 'Content', 'Campaign Date']);
            fputcsv($handle, ['Ashish Kumar', 'ashish@example.com', 'Sample content for campaign', '2024-10-01']);
            fputcsv($handle, ['Paras Kumar', 'paras@example.com', 'Another sample content', '2024-10-05']);
            fclose($handle);
        });
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
        return $response;
    }
}
