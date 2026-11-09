<?php

namespace App\Filament\Resources\ResultRegistrationResource\Pages;

use App\Exports\ResultRegistrationsExport;
use App\Filament\Resources\ResultRegistrationResource;
use App\Models\Exam;
use App\Models\ResultRegistration;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListResultRegistrations extends ListRecords
{
    protected static string $resource = ResultRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download_all')
                ->label('Download Excel')->icon('heroicon-o-arrow-down-tray')
                ->action(fn () => Excel::download(
                    new ResultRegistrationsExport(),
                    'result-registrations-'.now()->format('Ymd-His').'.xlsx'
                )),

            Actions\Action::make('download_by_zone')
                ->label('Download Excel by zone')->icon('heroicon-o-map')->color('info')
                ->form([
                    \Filament\Forms\Components\Select::make('exam_id')->label('Exam')
                        ->options(fn () => Exam::pluck('name', 'id')->toArray())->placeholder('All exams'),
                    \Filament\Forms\Components\Select::make('zone')
                        ->options(fn () => ResultRegistration::query()->distinct()->whereNotNull('zone')
                            ->pluck('zone', 'zone')->toArray())->placeholder('All zones'),
                ])
                ->action(fn (array $data) => Excel::download(
                    new ResultRegistrationsExport($data['exam_id'] ?: null, $data['zone'] ?: null),
                    'result-registrations-'.($data['zone'] ?: 'all').'-'.now()->format('Ymd-His').'.xlsx'
                )),

            Actions\Action::make('empty_data')
                ->label('Empty data')->color('danger')->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->modalDescription('This permanently deletes ALL registration data. Download a backup first.')
                ->action(fn () => ResultRegistration::query()->delete()),

            Actions\CreateAction::make()->label('Add registration data'),
        ];
    }
}
