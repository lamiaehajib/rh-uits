<x-app-layout>
    <style>
        .category-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .file-upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .file-upload-area:hover {
            border-color: #C2185B;
            background: rgba(194, 24, 91, 0.05);
        }
        
        .file-upload-area.drag-over {
            border-color: #C2185B;
            background: rgba(194, 24, 91, 0.1);
        }
    </style>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="mb-8 flex flex-wrap justify-between items-center gap-4">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">
                        <i class="fas fa-chart-bar mr-3"></i>Dépenses Variables
                    </h1>
                    <p class="text-white text-opacity-90">Gestion des dépenses fluctuantes</p>
                </div>
                
                <div class="flex gap-3">
                    <form method="GET" class="flex items-center gap-2">
                        <input type="month" name="mois" value="{{ $mois }}" 
                               class="form-control-custom bg-white text-gray-700 font-semibold">
                        <select name="categorie" class="form-control-custom bg-white text-gray-700 font-semibold">
                            <option value="">Toutes catégories</option>
                            @foreach(\App\Models\DepenseVariable::$categories as $key => $label)
                                <option value="{{ $key }}" {{ request('categorie') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="action-btn bg-white text-pink-700 hover:bg-pink-50">
                            <i class="fas fa-filter"></i> Filtrer
                        </button>
                    </form>
                    
                    <button class="action-btn bg-gradient-to-r from-pink-600 to-red-600 text-white ripple-btn" 
                            data-bs-toggle="modal" data-bs-target="#modalAjouterDepense">
                        <i class="fas fa-plus-circle"></i> Ajouter Dépense
                    </button>
                </div>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 rounded-lg p-4 shadow-md animate-fade-in">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 text-2xl mr-3"></i>
                    <p class="text-green-800 font-semibold">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            {{-- Total Card --}}
            <div class="mb-6">
                <div class="total-badge text-white">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-semibold opacity-90 mb-1">Total du mois</p>
                            <h2 class="text-5xl font-bold">{{ number_format($total, 2) }} DH</h2>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-6">
                            <i class="fas fa-hand-holding-usd text-5xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full table-modern">
                        <thead>
                            <tr class="text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <th class="px-6 py-4 text-left font-bold">Type</th>
                                <th class="px-6 py-4 text-left font-bold">Bénéficiaire</th>
                                <th class="px-6 py-4 text-left font-bold">Montant</th>
                                <th class="px-6 py-4 text-left font-bold">Date</th>
                                <th class="px-6 py-4 text-left font-bold">Catégorie</th>
                                <th class="px-6 py-4 text-left font-bold">Justificatif</th>
                                <th class="px-6 py-4 text-center font-bold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($depenses as $depense)
                            <tr>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-800">{{ $depense->type }}</span>
                                    @if($depense->description)
                                        <p class="text-xs text-gray-500 mt-1">{{ Str::limit($depense->description, 40) }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($depense->beneficiaire)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-pink-100 text-pink-800 text-sm font-semibold">
                                            <i class="fas fa-user-tie mr-2"></i>{{ $depense->beneficiaire->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xl font-bold text-pink-600">{{ number_format($depense->montant, 2) }} DH</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-gray-700 font-medium">
                                        <i class="far fa-calendar-alt mr-2"></i>{{ $depense->date_depense->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $categoryColors = [
                                            'primes_repos' => 'bg-blue-100 text-blue-800',
                                            'achats_equipements' => 'bg-green-100 text-green-800',
                                            'produits_menages' => 'bg-yellow-100 text-yellow-800',
                                            'frais_bancaires' => 'bg-red-100 text-red-800',
                                            'publications' => 'bg-purple-100 text-purple-800',
                                            'autres' => 'bg-gray-100 text-gray-800'
                                        ];
                                    @endphp
                                    <span class="category-badge {{ $categoryColors[$depense->categorie] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ \App\Models\DepenseVariable::$categories[$depense->categorie] ?? $depense->categorie }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    {{-- @if($depense->justificatif)
                                        <a href="{{ Storage::url($depense->justificatif) }}" target="_blank" 
                                           class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition">
                                            <i class="fas fa-paperclip mr-2"></i>Voir
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif --}}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button onclick="editDepense({{ $depense->id }}, {{ json_encode($depense) }})" 
                                                class="text-blue-600 hover:text-blue-800 transition p-2 hover:bg-blue-50 rounded-lg">
                                            <i class="fas fa-edit text-lg"></i>
                                        </button>
                                        <form action="{{ route('depenses.variables.destroy', $depense) }}" method="POST" 
                                              onsubmit="return confirm('Voulez-vous vraiment supprimer cette dépense?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 transition p-2 hover:bg-red-50 rounded-lg">
                                                <i class="fas fa-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                                        <p class="text-gray-500 text-lg">Aucune dépense variable pour ce mois</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $depenses->links() }}
                </div>
            </div>

        </div>
    </div>

    {{-- Modal Ajouter --}}
    <div class="modal fade modal-custom" id="modalAjouterDepense" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('depenses.variables.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <h5 class="modal-title text-xl font-bold">
                            <i class="fas fa-plus-circle mr-2"></i>Ajouter une Dépense Variable
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Type *</label>
                                <select name="type" class="form-control-custom w-full" required>
                                    <option value="">Choisir le type...</option>
                                    @foreach(\App\Models\DepenseVariable::$types as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Catégorie *</label>
                                <select name="categorie" class="form-control-custom w-full" required>
                                    <option value="">Choisir la catégorie...</option>
                                    @foreach(\App\Models\DepenseVariable::$categories as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Bénéficiaire</label>
                                <select name="beneficiaire_id" class="form-control-custom w-full">
                                    <option value="">Aucun</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Montant (DH) *</label>
                                <input type="number" name="montant" class="form-control-custom w-full" step="0.01" required placeholder="0.00">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Date *</label>
                                <input type="date" name="date_depense" class="form-control-custom w-full" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                                <input type="text" name="description" class="form-control-custom w-full" placeholder="Description...">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Notes</label>
                                <textarea name="notes" class="form-control-custom w-full" rows="2" placeholder="Notes additionnelles..."></textarea>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Justificatif (Facture/Reçu)</label>
                                <div class="file-upload-area" onclick="document.getElementById('file-input').click()">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                                    <p class="text-gray-600 font-semibold">Cliquer pour uploader un fichier</p>
                                    <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (Max 5MB)</p>
                                    <input type="file" id="file-input" name="justificatif" class="hidden" 
                                           accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                                <p id="file-name" class="text-sm text-gray-600 mt-2"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-gray-50 p-4">
                        <button type="button" class="action-btn bg-gray-200 text-gray-700 hover:bg-gray-300" data-bs-dismiss="modal">
                            <i class="fas fa-times mr-2"></i>Annuler
                        </button>
                        <button type="submit" class="action-btn bg-gradient-to-r from-pink-600 to-red-600 text-white ripple-btn">
                            <i class="fas fa-save mr-2"></i>Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Modifier --}}
    <div class="modal fade modal-custom" id="modalModifierDepense" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formModifier" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <h5 class="modal-title text-xl font-bold">
                            <i class="fas fa-edit mr-2"></i>Modifier la Dépense Variable
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Type *</label>
                                <select name="type" id="edit_type" class="form-control-custom w-full" required>
                                    @foreach(\App\Models\DepenseVariable::$types as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Catégorie *</label>
                                <select name="categorie" id="edit_categorie" class="form-control-custom w-full" required>
                                    @foreach(\App\Models\DepenseVariable::$categories as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Bénéficiaire</label>
                                <select name="beneficiaire_id" id="edit_beneficiaire_id" class="form-control-custom w-full">
                                    <option value="">Aucun</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Montant (DH) *</label>
                                <input type="number" name="montant" id="edit_montant" class="form-control-custom w-full" step="0.01" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Date *</label>
                                <input type="date" name="date_depense" id="edit_date_depense" class="form-control-custom w-full" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                                <input type="text" name="description" id="edit_description" class="form-control-custom w-full">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Notes</label>
                                <textarea name="notes" id="edit_notes" class="form-control-custom w-full" rows="2"></textarea>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nouveau Justificatif</label>
                                <input type="file" name="justificatif" class="form-control-custom w-full" 
                                       accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-gray-50 p-4">
                        <button type="button" class="action-btn bg-gray-200 text-gray-700 hover:bg-gray-300" data-bs-dismiss="modal">
                            <i class="fas fa-times mr-2"></i>Annuler
                        </button>
                        <button type="submit" class="action-btn bg-gradient-to-r from-blue-600 to-blue-800 text-white ripple-btn">
                            <i class="fas fa-save mr-2"></i>Modifier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // File input handler
        document.getElementById('file-input')?.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                document.getElementById('file-name').textContent = '✓ ' + fileName;
            }
        });
        
        function editDepense(id, depense) {
            document.getElementById('formModifier').action = `/depenses/variables/${id}`;
            document.getElementById('edit_type').value = depense.type;
            document.getElementById('edit_categorie').value = depense.categorie;
            document.getElementById('edit_beneficiaire_id').value = depense.beneficiaire_id || '';
            document.getElementById('edit_montant').value = depense.montant;
            document.getElementById('edit_date_depense').value = depense.date_depense;
            document.getElementById('edit_description').value = depense.description || '';
            document.getElementById('edit_notes').value = depense.notes || '';
            
            new bootstrap.Modal(document.getElementById('modalModifierDepense')).show();
        }
    </script>
    @endpush
</x-app-layout>