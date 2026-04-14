@php
$features = [
  [
    'id'    => 'parcelles',
    'label' => __('Parcelles illimitées'),
    'fa'    => 'fa-regular fa-map',
    'title' => __('Tout votre patrimoine. Un seul endroit.'),
    'pitch' => __("Qu'il s'agisse d'un seul bien ou d'un portefeuille réparti sur plusieurs cantons, BAT-ID vous permet d'enregistrer l'ensemble de vos parcelles sans aucune limite. Chaque bien est géolocalisé, référencé et consultable en quelques secondes, depuis n'importe quel appareil. Fini les registres épars, les recherches manuelles et les données incomplètes — votre patrimoine foncier est enfin centralisé, précis et toujours à jour."),
    'plans' => [
      ['name' => __('Propriétaire'),        'value' => '∞',  'type' => 'value'],
      ['name' => __('Famille & patrimoine'), 'value' => '∞',  'type' => 'value'],
      ['name' => __('Pro & investisseur'),   'value' => '∞',  'type' => 'value', 'featured' => true],
    ],
  ],
  [
    'id'    => 'veille-officielle',
    'label' => __('Veille officielle'),
    'fa'    => 'fa-regular fa-bell',
    'title' => __('Ne manquez plus rien de ce qui touche vos biens.'),
    'pitch' => __("Chaque jour, des décisions administratives, des enquêtes publiques et des modifications réglementaires sont publiées dans les bulletins officiels suisses — fédéraux, cantonaux et communaux. Sans veille active, ces publications passent inaperçues, parfois au détriment de vos intérêts. BAT-ID surveille automatiquement l'environnement juridique et foncier de vos parcelles sélectionnées et vous alerte dès qu'une publication vous concerne. Une protection passive, continue, et sans effort de votre part."),
    'plans' => [
      ['name' => __('Propriétaire'),        'value' => '5 '.__('parcelles'),  'type' => 'value'],
      ['name' => __('Famille & patrimoine'), 'value' => '20 '.__('parcelles'), 'type' => 'value'],
      ['name' => __('Pro & investisseur'),   'value' => '50 '.__('parcelles'), 'type' => 'value', 'featured' => true],
    ],
  ],
  [
    'id'    => 'veille-robotisee',
    'label' => __('Veille robotisée'),
    'fa'    => 'fa-solid fa-robot',
    'title' => __('Votre robot de veille travaille pendant que vous dormez.'),
    'pitch' => __("Au-delà des bulletins officiels, BAT-ID déploie un robot d'exploration qui collecte en continu toutes les informations publiques pertinentes autour de vos parcelles : règlements de construction, restrictions de droit public (RDPPF), zones protégées, potentiel de développement, données communales. Ces informations sont synthétisées automatiquement dans des fiches claires par parcelle. Vous bénéficiez d'une connaissance approfondie de votre patrimoine et de son environnement réglementaire, sans effectuer la moindre recherche vous-même."),
    'plans' => [
      ['name' => __('Propriétaire'),        'value' => __('Incluse'), 'type' => 'check'],
      ['name' => __('Famille & patrimoine'), 'value' => __('Incluse'), 'type' => 'check'],
      ['name' => __('Pro & investisseur'),   'value' => __('Incluse'), 'type' => 'check', 'featured' => true],
    ],
  ],
  [
    'id'    => 'stockage',
    'label' => __('Stockage sécurisé'),
    'fa'    => 'fa-regular fa-hard-drive',
    'title' => __("L'espace qu'il vous faut, là où vous en avez besoin."),
    'pitch' => __("Photos de chantier, factures d'artisans, actes notariés, contrats de bail — la gestion d'un bien immobilier génère une masse documentaire considérable. BAT-ID vous offre un espace de stockage sécurisé, dimensionné selon votre usage, pour conserver l'intégralité de vos documents liés à chaque parcelle. Chaque fichier est horodaté, versionné et accessible instantanément. Votre mémoire documentaire est enfin à la hauteur de la valeur de votre patrimoine."),
    'plans' => [
      ['name' => __('Propriétaire'),        'value' => '10 Go',  'type' => 'value'],
      ['name' => __('Famille & patrimoine'), 'value' => '60 Go',  'type' => 'value'],
      ['name' => __('Pro & investisseur'),   'value' => '100 Go', 'type' => 'value', 'featured' => true],
    ],
  ],
  [
    'id'    => 'cloud',
    'label' => __('Cloud externe'),
    'fa'    => 'fa-solid fa-cloud',
    'title' => __("Vos documents restent là où vous avez l'habitude de les gérer."),
    'pitch' => __("Vous utilisez déjà un service de stockage en ligne ? BAT-ID se connecte nativement à votre cloud existant — kDrive, Google Drive, OneDrive, Dropbox ou Proton Drive — et établit directement les liaisons entre vos documents et vos parcelles, sans vous imposer de changer vos habitudes. Pour ceux qui privilégient la souveraineté des données, kDrive d'Infomaniak garantit un hébergement intégralement en Suisse, conforme aux exigences les plus strictes en matière de confidentialité."),
    'plans' => [
      ['name' => __('Propriétaire'),        'value' => __('Inclus'), 'type' => 'check'],
      ['name' => __('Famille & patrimoine'), 'value' => __('Inclus'), 'type' => 'check'],
      ['name' => __('Pro & investisseur'),   'value' => __('Inclus'), 'type' => 'check', 'featured' => true],
    ],
  ],
  [
    'id'    => 'sauvegarde',
    'label' => __('Lot de sauvegarde'),
    'fa'    => 'fa-solid fa-shield-halved',
    'title' => __("Vos données toujours protégées, quoi qu'il arrive."),
    'pitch' => __("Votre patrimoine a de la valeur — vos données aussi. Le lot de sauvegarde inclus dans votre abonnement garantit que l'ensemble de vos documents, alertes, annotations et informations patrimoniales sont régulièrement sauvegardés de manière sécurisée. En cas d'incident, de perte ou de changement d'appareil, vous retrouvez l'intégralité de votre espace BAT-ID tel que vous l'avez laissé. Une tranquillité d'esprit que vous ne devriez jamais avoir à négliger."),
    'plans' => [
      ['name' => __('Propriétaire'),        'value' => __('Inclus'), 'type' => 'check'],
      ['name' => __('Famille & patrimoine'), 'value' => __('Inclus'), 'type' => 'check'],
      ['name' => __('Pro & investisseur'),   'value' => __('Inclus'), 'type' => 'check', 'featured' => true],
    ],
  ],
  [
    'id'    => 'workspace',
    'label' => __('Workspace collaboratif'),
    'fa'    => 'fa-solid fa-people-group',
    'title' => __('Gérez votre patrimoine à plusieurs, en toute clarté.'),
    'pitch' => __("Que vous soyez une famille souhaitant impliquer plusieurs membres dans la gestion d'un patrimoine commun, ou une structure professionnelle coordonnant des équipes sur plusieurs portefeuilles clients, BAT-ID vous propose des espaces de travail collaboratifs structurés. Chaque membre dispose d'un niveau d'accès défini — administrateur, gestionnaire, consultant ou lecteur — et accède en temps réel aux parcelles, documents et alertes qui le concernent. Les workspaces professionnels sont totalement étanches entre eux, garantissant la confidentialité de chaque portefeuille client."),
    'plans' => [
      ['name' => __('Propriétaire'),        'value' => '—',           'type' => 'none'],
      ['name' => __('Famille & patrimoine'), 'value' => '5 '.__('sièges'), 'type' => 'value'],
      ['name' => __('Pro & investisseur'),   'value' => '∞ '.__('sièges'), 'type' => 'value', 'featured' => true],
    ],
  ],
];
@endphp

<section
  class="fe-section"
  x-data="{ active: null }"
>
  {{-- INTRO --}}
  <div class="fe-intro">
    <p class="fe-eyebrow">
      <span class="fe-eyebrow__line"></span>
      {{ __('Fonctionnalités incluses') }}
      <span class="fe-eyebrow__line"></span>
    </p>
    <h2 class="fe-title">
      {!! __('Ce que chaque fonctionnalité<br>fait concrètement pour vous') !!}
    </h2>
    <p class="fe-subtitle">
      {{ __("Sélectionnez une fonctionnalité pour découvrir ce qu'elle apporte à votre gestion patrimoniale et ce qui est prévu dans chaque formule.") }}
    </p>
  </div>

  {{-- PILLS --}}
  <nav class="fe-pills" aria-label="{{ __('Fonctionnalités BAT-ID') }}">
    @foreach ($features as $feature)
      <button
        class="fe-pill"
        :class="{ 'fe-pill--active': active === '{{ $feature['id'] }}' }"
        @click="active = (active === '{{ $feature['id'] }}') ? null : '{{ $feature['id'] }}'"
        :aria-pressed="active === '{{ $feature['id'] }}'"
        type="button"
      >
        <i class="{{ $feature['fa'] }} fe-pill__icon"></i>
        {{ $feature['label'] }}
      </button>
    @endforeach
  </nav>

  {{-- PANELS --}}
  @foreach ($features as $feature)
    <div
      class="fe-panel-wrap"
      x-show="active === '{{ $feature['id'] }}'"
      x-transition:enter="fe-enter"
      x-transition:enter-start="fe-enter-start"
      x-transition:enter-end="fe-enter-end"
      x-transition:leave="fe-leave"
      x-cloak
    >
      <div class="fe-card">

        {{-- GAUCHE : pitch --}}
        <div class="fe-pitch">
          <div class="fe-pitch__icon">
            <i class="{{ $feature['fa'] }}"></i>
          </div>
          <h3 class="fe-pitch__title">{{ $feature['title'] }}</h3>
          <p class="fe-pitch__text">{{ $feature['pitch'] }}</p>
        </div>

        {{-- DROITE : tableau formules --}}
        <div class="fe-plans">
          <p class="fe-plans__label">{{ __('Volumes par formule') }}</p>
          <div class="fe-plans__rows">
            @foreach ($feature['plans'] as $plan)
              <div
                class="fe-plan-row {{ ($plan['featured'] ?? false) ? 'fe-plan-row--featured' : '' }}"
              >
                <span class="fe-plan-row__name">{{ $plan['name'] }}</span>
                <span class="fe-plan-row__val">
                  @if ($plan['type'] === 'check')
                    <svg class="fe-ico-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span>{{ $plan['value'] }}</span>
                  @elseif ($plan['type'] === 'none')
                    <svg class="fe-ico-dash" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                      <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                  @else
                    <span>{{ $plan['value'] }}</span>
                  @endif
                </span>
              </div>
            @endforeach
          </div>
        </div>

      </div>
    </div>
  @endforeach

  {{-- BADGE SUISSE --}}
  <div class="fe-badge">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
      <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
    </svg>
    {{ __('Développement, serveurs et données — 100% Suisse') }}
  </div>

</section>
