{{-- resources/views/image_preuve/edit.blade.php --}}
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
    .form-control:focus {
        border-color: #D32F2F;
        box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.25);
    }
    .form-label {
        font-weight: 500;
    }
    .animation-fade-in {
        animation: fadeIn 0.6s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card animation-fade-in">
                <div class="card-header">
                    <span><i class="fas fa-edit me-2"></i>Modifier l'Image Preuve</span>
                    <a href="{{ route('image_preuve.index') }}" class="btn btn-light btn-sm"><i class="fas fa-arrow-left me-1"></i>Retour</a>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Oops!</strong> Il y a eu des problèmes avec votre entrée.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('image_preuve.update', $imagePreuve->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="titre" class="form-label"><i class="fas fa-heading me-2"></i>Titre:</label>
                            <input type="text" name="titre" class="form-control" id="titre" value="{{ old('titre', $imagePreuve->titre) }}" placeholder="Entrez le titre">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label"><i class="fas fa-align-left me-2"></i>Description:</label>
                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Entrez la description">{{ old('description', $imagePreuve->description) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="media" class="form-label"><i class="fas fa-file-upload me-2"></i>Fichier Média (Image/Vidéo):</label>
                            <input type="file" name="media" class="form-control" id="media">
                            <div class="form-text">Formats supportés : JPG, PNG, GIF, SVG, MP4, MKV, AVI, MOV. Max 50MB. Laissez vide pour conserver le fichier actuel.</div>
                            @if($imagePreuve->media)
                                <div class="mt-2">
                                    <p>Fichier actuel:
                                        @php
                                            $fileName = basename($imagePreuve->media);
                                            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                                        @endphp

                                        @if(in_array(strtolower($fileExtension), ['jpeg', 'png', 'jpg', 'gif', 'svg']))
                                            <i class="fas fa-image me-1"></i> {{ $fileName }}
                                        @elseif(in_array(strtolower($fileExtension), ['mp4', 'mkv', 'avi', 'mov']))
                                            <i class="fas fa-video me-1"></i> {{ $fileName }}
                                        @else
                                            <i class="fas fa-file me-1"></i> {{ $fileName }}
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label"><i class="fas fa-calendar-alt me-2"></i>Date:</label>
                            <input type="date" name="date" class="form-control" id="date" value="{{ old('date', $imagePreuve->date) }}">
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-sync-alt me-2"></i>Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>
