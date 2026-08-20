<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $title = 'Photos';

    protected static ?string $modelLabel = 'photo';

    protected static ?string $pluralModelLabel = 'photos';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('path')
                    ->label('Photos')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->disk('public')
                    ->directory('products')
                    ->imageResizeMode('cover')
                    ->imageResizeTargetWidth(1600)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('path')
            ->columns([
                ImageColumn::make('path')
                    ->label('Aperçu'),
                IconColumn::make('is_primary')
                    ->label('Principale')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->using(function (array $data): Model {
                        $paths = (array) $data['path'];
                        $hasPrimary = $this->getOwnerRecord()->images()->where('is_primary', true)->exists();
                        $nextOrder = $this->getOwnerRecord()->images()->max('sort_order') + 1;

                        $first = null;

                        foreach (array_values($paths) as $i => $path) {
                            $record = $this->getOwnerRecord()->images()->create([
                                'path' => $path,
                                'sort_order' => $nextOrder + $i,
                                'is_primary' => ! $hasPrimary && $i === 0,
                            ]);

                            $first ??= $record;
                        }

                        return $first;
                    }),
            ])
            ->recordActions([
                Action::make('setPrimary')
                    ->label('Définir comme principale')
                    ->icon(Heroicon::Star)
                    ->visible(fn (Model $record) => ! $record->is_primary)
                    ->action(function (Model $record) {
                        $record->product->images()->update(['is_primary' => false]);
                        $record->update(['is_primary' => true]);
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Aucune photo')
            ->emptyStateDescription('Ajoute au moins une photo pour que le produit s\'affiche correctement sur le site.');
    }
}
