<x-app-layout>
<style>
    body {
        background-color: #f8f9fa;
    }
    .card {
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }
    .card-header {
        background-color: #D32F2F;
        color: white;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        padding: 1rem 1.5rem;
        font-size: 1.25rem;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .btn-primary {
        background-color: #D32F2F;
        border-color: #D32F2F;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #bb2828;
        border-color: #bb2828;
    }
    .media-preview {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin-top: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        animation: popIn 0.5s ease-out;
    }
    .video-preview {
        width: 100%;
        max-height: 500px; /* Limit video height */
        border-radius: 8px;
        margin-top: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        animation: popIn 0.5s ease-out;
    }
    @keyframes popIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }
    .detail-item {
        margin-bottom: 15px;
    }
    .detail-item strong {
        color: #333;
        font-size: 1.1em;
    }
    .detail-item span {
        color: #555;
    }
</style>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <span><i class="fas fa-info-circle me-2"></i>Détails de l'Image Preuve</span>
                    <a href="{{ route('image_preuve.index') }}" class="btn btn-light btn-sm"><i class="fas fa-arrow-left me-1"></i>Retour</a>
                </div>
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="detail-item">
                                <strong><i class="fas fa-heading me-2"></i>Titre:</strong>
                                <span>{{ $imagePreuve->titre }}</span>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="detail-item">
                                <strong><i class="fas fa-align-left me-2"></i>Description:</strong>
                                <span>{{ $imagePreuve->description }}</span>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="detail-item">
                                <strong><i class="fas fa-calendar-alt me-2"></i>Date:</strong>
                                <span>{{ \Carbon\Carbon::parse($imagePreuve->date)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="detail-item">
                                <strong><i class="fas fa-user me-2"></i>Utilisateur:</strong>
                                <span>{{ $imagePreuve->user ? $imagePreuve->user->name : 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-3">
                            @php
                                // Obtient le chemin stocké, ex: 'public/media/yourfile.mp4'
                                $storedPath = $imagePreuve->media;
                                // Extrait l'extension du fichier à partir du chemin stocké (plus robuste)
                                $fileExtension = pathinfo($storedPath, PATHINFO_EXTENSION);
                                // Génère l'URL publique pour le navigateur, ex: '/storage/media/yourfile.mp4'
                                $mediaUrl = Storage::url($storedPath);
                            @endphp

                            @if(in_array(strtolower($fileExtension), ['jpeg', 'png', 'jpg', 'gif', 'svg']))
                                <img src="{{ $mediaUrl }}" alt="{{ $imagePreuve->titre }}" class="img-fluid media-preview">
                            @elseif(in_array(strtolower($fileExtension), ['mp4', 'mkv', 'avi', 'mov', 'webm']))
                                <video controls class="video-preview" preload="metadata">
                                    {{-- Spécifie le type MIME pour une meilleure compatibilité --}}
                                    @if(strtolower($fileExtension) == 'mp4')
                                        <source src="{{ $mediaUrl }}" type="video/mp4">
                                    @elseif(strtolower($fileExtension) == 'mkv')
                                        <source src="{{ $mediaUrl }}" type="video/x-matroska">
                                    @elseif(strtolower($fileExtension) == 'avi')
                                        <source src="{{ $mediaUrl }}" type="video/x-msvideo">
                                    @elseif(strtolower($fileExtension) == 'mov')
                                        <source src="{{ $mediaUrl }}" type="video/quicktime">
                                    @elseif(strtolower($fileExtension) == 'webm')
                                        <source src="{{ $mediaUrl }}" type="video/webm">
                                    @endif
                                    {{-- Balise track pour les sous-titres (si tu en ajoutes plus tard) --}}
                                    {{-- <track kind="captions" src="/path/to/your/captions.vtt" srclang="fr" label="Français"> --}}
                                    <p class="alert alert-warning mt-2">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Votre navigateur ne supporte pas la balise vidéo.
                                        <a href="{{ route('image_preuve.download', $imagePreuve->id) }}" class="btn btn-sm btn-primary mt-2">
                                            <i class="fas fa-download me-1"></i>Télécharger le fichier
                                        </a>
                                    </p>
                                </video>
                            @else
                                <p class="alert alert-warning mt-2">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Type de média non pris en charge pour l'aperçu.
                                    <a href="{{ route('image_preuve.download', $imagePreuve->id) }}" class="btn btn-sm btn-primary mt-2">
                                        <i class="fas fa-download me-1"></i>Télécharger le fichier
                                    </a>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>