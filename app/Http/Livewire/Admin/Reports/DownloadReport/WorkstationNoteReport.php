<?php
namespace App\Http\Livewire\Admin\Reports\DownloadReport;
use Livewire\Component;
class WorkstationNoteReport extends Component
{  /* render the page */
    public function render()
    {
        return view('livewire.admin.reports.download-report.workstationnote-report')      
        ->extends('layouts.print-layout')
        ->section('content');
    }
}