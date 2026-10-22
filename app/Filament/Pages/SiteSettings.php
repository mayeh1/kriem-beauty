<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Site Content';
    protected static ?string $title = 'Site Content';
    protected static ?int $navigationSort = 10;
    protected static string $view = 'filament.pages.site-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $this->form->fill($settings);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Announcement Banner')
                    ->description('Show a banner across the top of the site')
                    ->schema([
                        Toggle::make('announcement_active')->label('Show Banner'),
                        Textarea::make('announcement_text')->label('Banner Text')->rows(2),
                    ])->columns(1),

                Section::make('Hero Section')
                    ->description('The main banner on the homepage')
                    ->schema([
                        TextInput::make('hero_title')->label('Title'),
                        Textarea::make('hero_subtitle')->label('Subtitle')->rows(2),
                        TextInput::make('hero_cta_text')->label('Button Text'),
                        TextInput::make('hero_cta_link')->label('Button Link'),
                    ])->columns(2),

                Section::make('Contact Page')
                    ->schema([
                        TextInput::make('contact_heading')->label('Page Heading'),
                        Textarea::make('contact_subheading')->label('Subheading')->rows(2),
                        TextInput::make('contact_email')->label('Email'),
                        TextInput::make('contact_phone')->label('Phone'),
                        Textarea::make('contact_address')->label('Address')->rows(2),
                    ])->columns(2),

                Section::make('Footer')
                    ->schema([
                        Textarea::make('footer_about')->label('About Text')->rows(2),
                        TextInput::make('copyright_text')->label('Copyright Text'),
                    ])->columns(1),

                Section::make('Shipping')
                    ->schema([
                        TextInput::make('shipping_info_text')->label('Shipping Info (shown on product pages)'),
                    ]),

                Section::make('Social Media Links')
                    ->schema([
                        TextInput::make('social_instagram')->label('Instagram URL')->url(),
                        TextInput::make('social_facebook')->label('Facebook URL')->url(),
                        TextInput::make('social_tiktok')->label('TikTok URL')->url(),
                    ])->columns(3),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value ?? '']);
        }

        Setting::clearCache();

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }
}
