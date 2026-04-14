<?php

namespace Database\Seeders;

use App\Models\SubscriptionType;
use App\Models\SubscriptionTypeTranslation;
use Illuminate\Database\Seeder;

class SubscriptionTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'status' => 'online',
                'sort_order' => 1,
                'parcelles_count' => 10,
                'parcelles_unlimited' => false,
                'alertes_count' => 5,
                'stockage_go' => 2,
                'stockage_unlimited' => false,
                'cloud_externe' => false,
                'lot_sauvegarde' => false,
                'workspace_enabled' => false,
                'workspace_count' => null,
                'workspace_unlimited' => false,
                'price_chf' => 49.00,
                'discount_36_months' => 0,
                'translations' => [
                    'fr' => ['name' => 'Starter', 'description' => "L'essentiel pour débuter"],
                    'de' => ['name' => 'Starter', 'description' => 'Das Wesentliche für den Einstieg'],
                    'it' => ['name' => 'Starter', 'description' => "L'essenziale per iniziare"],
                    'en' => ['name' => 'Starter', 'description' => 'The essentials to get started'],
                ],
            ],
            [
                'status' => 'online',
                'sort_order' => 2,
                'parcelles_count' => 100,
                'parcelles_unlimited' => false,
                'alertes_count' => 50,
                'stockage_go' => 20,
                'stockage_unlimited' => false,
                'cloud_externe' => true,
                'lot_sauvegarde' => true,
                'workspace_enabled' => true,
                'workspace_count' => 3,
                'workspace_unlimited' => false,
                'price_chf' => 149.00,
                'discount_36_months' => 10.00,
                'translations' => [
                    'fr' => ['name' => 'Premium', 'description' => 'Pour les professionnels exigeants'],
                    'de' => ['name' => 'Premium', 'description' => 'Für anspruchsvolle Profis'],
                    'it' => ['name' => 'Premium', 'description' => 'Per i professionisti esigenti'],
                    'en' => ['name' => 'Premium', 'description' => 'For demanding professionals'],
                ],
            ],
            [
                'status' => 'online',
                'sort_order' => 3,
                'parcelles_count' => null,
                'parcelles_unlimited' => true,
                'alertes_count' => 999,
                'stockage_go' => null,
                'stockage_unlimited' => true,
                'cloud_externe' => true,
                'lot_sauvegarde' => true,
                'workspace_enabled' => true,
                'workspace_count' => null,
                'workspace_unlimited' => true,
                'price_chf' => 349.00,
                'discount_36_months' => 15.00,
                'translations' => [
                    'fr' => ['name' => 'Enterprise', 'description' => 'La solution complète sans limites'],
                    'de' => ['name' => 'Enterprise', 'description' => 'Die komplette Lösung ohne Grenzen'],
                    'it' => ['name' => 'Enterprise', 'description' => 'La soluzione completa senza limiti'],
                    'en' => ['name' => 'Enterprise', 'description' => 'The complete solution without limits'],
                ],
            ],
        ];

        foreach ($types as $typeData) {
            $translations = $typeData['translations'];
            unset($typeData['translations']);

            $type = SubscriptionType::create($typeData);

            foreach ($translations as $locale => $trans) {
                SubscriptionTypeTranslation::create([
                    'subscription_type_id' => $type->id,
                    'locale' => $locale,
                    'name' => $trans['name'],
                    'description' => $trans['description'],
                ]);
            }
        }
    }
}
