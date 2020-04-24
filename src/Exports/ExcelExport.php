<?php


namespace Jeanderson\modeladministrator\Exports;


use Illuminate\Contracts\View\View;
use Jeanderson\modeladministrator\Models\ModelConfig;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExcelExport implements FromView,ShouldAutoSize,WithEvents, WithColumnFormatting
{
    /**
     * @var ModelConfig
     */
    private $modelConfig;
    private $id;

    /**
     * ExcelExport constructor.
     * @param ModelConfig $modelConfig
     * @param $id
     */
    public function __construct(ModelConfig $modelConfig, $id)
    {
        $this->modelConfig = $modelConfig;
        $this->id = $id;
    }


    /**
     * @inheritDoc
     */
    public function view(): View
    {
        $model = $this->modelConfig->model_class::find($this->id);
        return view("modeladmin::layout.excel.excel-view")->with(["model"=>$model,"modelConfig"=>$this->modelConfig]);
    }

    /**
     * @inheritDoc
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
                $cellRange = 'A2:W2'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }

    /**
     * @inheritDoc
     */
    public function columnFormats(): array
    {
        return [
            'A1:W1' => '@'
        ];
    }
}
