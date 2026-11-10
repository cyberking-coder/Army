<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class IconSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Icon Settings';
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'Icon &amp; Logo Settings';

    protected static string $view = 'filament.pages.icon-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'site_title'       => Setting::get('site_title'),
            'site_tagline'     => Setting::get('site_tagline'),
            'contact_email'    => Setting::get('contact_email'),
            'contact_phone'    => Setting::get('contact_phone'),
            'icon_favicon'     => Setting::get('icon_favicon'),
            'icon_apple_touch' => Setting::get('icon_apple_touch'),
            'icon_logo'        => Setting::get('icon_logo'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Site identity')
                    ->description('These appear on the website header, footer and browser tab.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('site_title')->label('Site title')->required()->maxLength(100),
                        TextInput::make('site_tagline')->label('Tagline')->maxLength(200),
                        TextInput::make('contact_email')->label('Contact email')->email()->maxLength(150),
                        TextInput::make('contact_phone')->label('Contact phone')->tel()->maxLength(30),
                    ]),

                Section::make('Icons')
                    ->description('Upload square images. Favicon is used in the browser tab; Apple touch icon when the site is added to iPhone home screen; Logo is shown in the site header.')
                    ->columns(3)
                    ->schema([
                        FileUpload::make('icon_favicon')
                            ->label('Favicon')
                            ->image()
                            ->directory('icons')
                            ->imageEditor()
                            ->helperText('Recommended: 32x32 or 64x64 .ico/.png'),
                        FileUpload::make('icon_apple_touch')
                            ->label('Apple touch icon')
                            ->image()
                            ->directory('icons')
                            ->imageEditor()
                            ->helperText('Recommended: 180x180 .png'),
                        FileUpload::make('icon_logo')
                            ->label('Site logo')
                            ->image()
                            ->directory('icons')
                            ->imageEditor()
                            ->helperText('Shown in the site header. PNG with transparent background works best.'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        foreach ($this->form->getState() as $key => $value) {
            Setting::set($key, $value);
        }

        Notification::make()
            ->success()
            ->title('Settings saved')
            ->body('Changes are live immediately on the public site.')
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('Save changes')
                ->submit('save'),
        ];
    }
}
