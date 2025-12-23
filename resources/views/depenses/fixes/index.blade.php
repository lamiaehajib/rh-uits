<x-app-layout>
    <style>
        .table-modern {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .table-modern thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .table-modern tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .table-modern tbody tr:hover {
            background: linear-gradient(90deg, rgba(194, 24, 91, 0.05) 0%, transparent 100%);
            transform: scale(1.01);
        }
        
        .action-btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .modal-custom .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        .modal-custom .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 1.5rem;
        }
        
        .form-control-custom {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control-custom:focus {
            border-color: #C2185B;
            box-shadow: 0 0 0 3px rgba(194, 24, 91, 0.1);
            outline: none;
        }
        
        .total-badge {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            padding: 1.5rem 2rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
    </style>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="mb-8 flex flex-wrap justify-between items-center gap-4">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">
                        <i class="fas fa-lock mr-3"></i>Dépenses Fixes
                    </h1>
                    <p class="text-white text-opacity-90">Gestion des charges fixes mensuelles</p>
                </div>
                
                <div class="flex gap-3">
                    <form method="GET" class="flex items-center gap-2">
                        <input type="month" name="mois" value="{{ $mois }}" 
                               class="form-control-custom bg-white text-gray-700 font-semibold">
                        <button type="submit" class="action-btn bg-white text-purple-700 hover:bg-purple-50">
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
                            <i class="fas fa-coins text-5xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full table-modern">
                        <thead>
                            <tr class="text-white">
                                <th class="px-6 py-4 text-left font-bold">Type</th>
                                <th class="px-6 py-4 text-left font-bold">Salarié</th>
                                <th class="px-6 py-4 text-left font-bold">Montant</th>
                                <th class="px-6 py-4 text-left font-bold">Date</th>
                                <th class="px-6 py-4 text-left font-bold">Statut</th>
                                <th class="px-6 py-4 text-left font-bold">Notes</th>
                                <th class="px-6 py-4 text-center font-bold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($depenses as $depense)
                            <tr>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-800">{{ $depense->type }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($depense->salarie)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-purple-100 text-purple-800 text-sm font-semibold">
                                            <i class="fas fa-user mr-2"></i>{{ $depense->salarie->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xl font-bold text-purple-600">{{ number_format($depense->montant, 2) }} DH</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-gray-700 font-medium">
                                        <i class="far fa-calendar-alt mr-2"></i>{{ $depense->date_depense->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($depense->statut == 'payé')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-bold">
                                            <i class="fas fa-check-circle mr-2"></i>Payé
                                        </span>
                                    @elseif($depense->statut == 'en_attente')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-sm font-bold">
                                            <i class="fas fa-clock mr-2"></i>En attente
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-800 text-sm font-bold">
                                            <i class="fas fa-times-circle mr-2"></i>Annulé
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($depense->notes)
                                        <span class="text-sm text-gray-600">{{ Str::limit($depense->notes, 30) }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button onclick="editDepense({{ $depense->id }}, {{ json_encode($depense) }})" 
                                                class="text-blue-600 hover:text-blue-800 transition p-2 hover:bg-blue-50 rounded-lg">
                                            <i class="fas fa-edit text-lg"></i>
                                        </button>
                                        <form action="{{ route('depenses.fixes.destroy', $depense) }}" method="POST" 
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
                                        <p class="text-gray-500 text-lg">Aucune dépense fixe pour ce mois</p>
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
                <form action="{{ route('depenses.fixes.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title text-xl font-bold">
                            <i class="fas fa-plus-circle mr-2"></i>Ajouter une Dépense Fixe
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Type *</label>
                                <select name="type" class="form-control-custom w-full" required>
                                    <option value="">Choisir le type...</option>
                                    @foreach(\App\Models\DepenseFixe::$types as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Salarié</label>
                                <select name="salarie_id" class="form-control-custom w-full">
                                    <option value="">Aucun</option>
                                    @foreach($salaries as $sal)
                                        <option value="{{ $sal->id }}">{{ $sal->name }}</option>
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
                                <label class="block text-sm font-bold text-gray-700 mb-2">Statut *</label>
                                <select name="statut" class="form-control-custom w-full" required>
                                    <option value="en_attente">En attente</option>
                                    <option value="payé">Payé</option>
                                    <option value="annulé">Annulé</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                                <input type="text" name="description" class="form-control-custom w-full" placeholder="Description...">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Notes</label>
                                <textarea name="notes" class="form-control-custom w-full" rows="3" placeholder="Notes additionnelles..."></textarea>
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
                <form id="formModifier" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title text-xl font-bold">
                            <i class="fas fa-edit mr-2"></i>Modifier la Dépense Fixe
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Type *</label>
                                <select name="type" id="edit_type" class="form-control-custom w-full" required>
                                    @foreach(\App\Models\DepenseFixe::$types as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Salarié</label>
                                <select name="salarie_id" id="edit_salarie_id" class="form-control-custom w-full">
                                    <option value="">Aucun</option>
                                    @foreach($salaries as $sal)
                                        <option value="{{ $sal->id }}">{{ $sal->name }}</option>
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
                                <label class="block text-sm font-bold text-gray-700 mb-2">Statut *</label>
                                <select name="statut" id="edit_statut" class="form-control-custom w-full" required>
                                    <option value="en_attente">En attente</option>
                                    <option value="payé">Payé</option>
                                    <option value="annulé">Annulé</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                                <input type="text" name="description" id="edit_description" class="form-control-custom w-full">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Notes</label>
                                <textarea name="notes" id="edit_notes" class="form-control-custom w-full" rows="3"></textarea>
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
        function editDepense(id, depense) {
            document.getElementById('formModifier').action = `/depenses/fixes/${id}`;
            document.getElementById('edit_type').value = depense.type;
            document.getElementById('edit_salarie_id').value = depense.salarie_id || '';
            document.getElementById('edit_montant').value = depense.montant;
            document.getElementById('edit_date_depense').value = depense.date_depense;
            document.getElementById('edit_statut').value = depense.statut;
            document.getElementById('edit_description').value = depense.description || '';
            document.getElementById('edit_notes').value = depense.notes || '';
            
            new bootstrap.Modal(document.getElementById('modalModifierDepense')).show();
        }
    </script>
    @endpush
</x-app-layout>