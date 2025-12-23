<x-app-layout>
    <style>
        .rapport-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .rapport-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }
        
        .print-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .print-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        
        .stat-row {
            padding: 1.5rem;
            border-bottom: 1px solid #f3f4f6;
            transition: all 0.3s ease;
        }
        
        .stat-row:hover {
            background: linear-gradient(90deg, rgba(194, 24, 91, 0.03) 0%, transparent 100%);
        }
        
        .stat-row:last-child {
            border-bottom: none;
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                background: white !important;
            }
            
            .rapport-card {
                box-shadow: none !important;
                page-break-inside: avoid;
            }
        }
    </style>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="mb-8 flex flex-wrap justify-between items-center gap-4 no-print">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">
                        <i class="fas fa-file-invoice mr-3"></i>Rapport Mensuel
                    </h1>
                    <p class="text-white text-opacity-90">Analyse détaillée des dépenses</p>
                </div>
                
                <div class="flex gap-3">
                    <form method="GET" class="flex items-center gap-2">
                        <input type="month" name="mois" value="{{ $mois }}" 
                               class="form-control-custom bg-white text-gray-700 font-semibold">
                        <button type="submit" class="action-btn bg-white text-purple-700 hover:bg-purple-50">
                            <i class="fas fa-calendar-alt"></i> Changer
                        </button>
                    </form>
                    
                    <button onclick="window.print()" class="print-btn ripple-btn">
                        <i class="fas fa-print mr-2"></i> Imprimer
                    </button>
                    
                    <button onclick="exportPDF()" class="action-btn bg-gradient-to-r from-red-600 to-pink-600 text-white ripple-btn">
                        <i class="fas fa-file-pdf mr-2"></i> Export PDF
                    </button>
                </div>
            </div>

            {{-- Période --}}
            <div class="mb-8 bg-white rounded-2xl shadow-lg p-6">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">
                        Rapport du mois: {{ \Carbon\Carbon::parse($mois . '-01')->locale('fr')->isoFormat('MMMM YYYY') }}
                    </h2>
                    <p class="text-gray-600">
                        Généré le {{ \Carbon\Carbon::now()->locale('fr')->isoFormat('LL à HH:mm') }}
                    </p>
                </div>
            </div>

            {{-- Vue d'ensemble --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                @php
                    $totalFixePaye = $rapportFixe->where('statut', 'payé')->sum('total');
                    $totalFixeAttente = $rapportFixe->where('statut', 'en_attente')->sum('total');
                    $totalVariable = $rapportVariable->sum('total');
                    $totalGeneral = $totalFixePaye + $totalVariable;
                @endphp
                
                <div class="rapport-card">
                    <div class="p-6 bg-gradient-to-br from-purple-500 to-purple-700 text-white">
                        <i class="fas fa-lock text-4xl mb-3 opacity-80"></i>
                        <p class="text-sm font-semibold opacity-90 mb-1">Dépenses Fixes Payées</p>
                        <h3 class="text-4xl font-bold">{{ number_format($totalFixePaye, 2) }} DH</h3>
                    </div>
                    <div class="p-4 bg-purple-50">
                        <p class="text-sm text-purple-700 font-semibold">
                            En attente: {{ number_format($totalFixeAttente, 2) }} DH
                        </p>
                    </div>
                </div>
                
                <div class="rapport-card">
                    <div class="p-6 bg-gradient-to-br from-pink-500 to-red-600 text-white">
                        <i class="fas fa-chart-line text-4xl mb-3 opacity-80"></i>
                        <p class="text-sm font-semibold opacity-90 mb-1">Dépenses Variables</p>
                        <h3 class="text-4xl font-bold">{{ number_format($totalVariable, 2) }} DH</h3>
                    </div>
                    <div class="p-4 bg-pink-50">
                        <p class="text-sm text-pink-700 font-semibold">
                            {{ $rapportVariable->count() }} transactions
                        </p>
                    </div>
                </div>
                
                <div class="rapport-card">
                    <div class="p-6 bg-gradient-to-br from-yellow-500 to-orange-600 text-white">
                        <i class="fas fa-calculator text-4xl mb-3 opacity-80"></i>
                        <p class="text-sm font-semibold opacity-90 mb-1">Total Général</p>
                        <h3 class="text-4xl font-bold">{{ number_format($totalGeneral, 2) }} DH</h3>
                    </div>
                    <div class="p-4 bg-yellow-50">
                        <p class="text-sm text-orange-700 font-semibold">
                            Fixes: {{ number_format(($totalFixePaye / max($totalGeneral, 1)) * 100, 1) }}% | Variables: {{ number_format(($totalVariable / max($totalGeneral, 1)) * 100, 1) }}%
                        </p>
                    </div>
                </div>
            </div>

            {{-- Détail Dépenses Fixes --}}
            <div class="rapport-card mb-8">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-purple-100">
                    <h3 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-list-ul text-purple-600 mr-3"></i>
                        Détail Dépenses Fixes
                    </h3>
                </div>
                
                <div class="p-6">
                    @php
                        $groupedFixe = $rapportFixe->groupBy('type');
                    @endphp
                    
                    @forelse($groupedFixe as $type => $items)
                        <div class="mb-6 border-b border-gray-100 pb-6 last:border-0 last:pb-0">
                            <h4 class="font-bold text-lg text-gray-800 mb-3">
                                <i class="fas fa-tag text-purple-600 mr-2"></i>{{ $type }}
                            </h4>
                            
                            @foreach($items as $item)
                                <div class="stat-row flex justify-between items-center bg-gray-50 rounded-lg mb-2">
                                    <div class="flex items-center gap-3">
                                        @if($item->statut == 'payé')
                                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                        @elseif($item->statut == 'en_attente')
                                            <i class="fas fa-clock text-yellow-500 text-xl"></i>
                                        @else
                                            <i class="fas fa-times-circle text-red-500 text-xl"></i>
                                        @endif
                                        
                                        <div>
                                            <p class="font-semibold text-gray-700">{{ ucfirst($item->statut) }}</p>
                                            <p class="text-sm text-gray-500">{{ $item->nombre }} transaction(s)</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-2xl font-bold text-purple-600">{{ number_format($item->total, 2) }} DH</p>
                                    </div>
                                </div>
                            @endforeach
                            
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <div class="flex justify-between items-center">
                                    <p class="font-bold text-gray-700">Sous-total {{ $type }}</p>
                                    <p class="text-2xl font-bold text-purple-700">{{ number_format($items->sum('total'), 2) }} DH</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-inbox text-5xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Aucune dépense fixe ce mois-ci</p>
                        </div>
                    @endforelse
                    
                    @if($rapportFixe->isNotEmpty())
                        <div class="mt-6 pt-6 border-t-2 border-purple-200">
                            <div class="flex justify-between items-center bg-purple-50 p-4 rounded-lg">
                                <p class="text-xl font-bold text-gray-800">TOTAL DÉPENSES FIXES PAYÉES</p>
                                <p class="text-3xl font-bold text-purple-700">{{ number_format($totalFixePaye, 2) }} DH</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Détail Dépenses Variables --}}
            <div class="rapport-card mb-8">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-pink-50 to-pink-100">
                    <h3 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-chart-bar text-pink-600 mr-3"></i>
                        Détail Dépenses Variables
                    </h3>
                </div>
                
                <div class="p-6">
                    @forelse($rapportVariable as $item)
                        <div class="stat-row flex justify-between items-center">
                            <div class="flex items-center gap-4">
                                @php
                                    $categoryIcons = [
                                        'primes_repos' => 'fa-gift',
                                        'achats_equipements' => 'fa-shopping-cart',
                                        'produits_menages' => 'fa-broom',
                                        'frais_bancaires' => 'fa-university',
                                        'publications' => 'fa-bullhorn',
                                        'autres' => 'fa-ellipsis-h'
                                    ];
                                    $categoryColors = [
                                        'primes_repos' => 'text-blue-500',
                                        'achats_equipements' => 'text-green-500',
                                        'produits_menages' => 'text-yellow-500',
                                        'frais_bancaires' => 'text-red-500',
                                        'publications' => 'text-purple-500',
                                        'autres' => 'text-gray-500'
                                    ];
                                @endphp
                                
                                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-pink-100 to-pink-200 flex items-center justify-center">
                                    <i class="fas {{ $categoryIcons[$item->categorie] ?? 'fa-question' }} text-2xl {{ $categoryColors[$item->categorie] ?? 'text-gray-500' }}"></i>
                                </div>
                                
                                <div>
                                    <p class="font-bold text-lg text-gray-800">
                                        {{ \App\Models\DepenseVariable::$categories[$item->categorie] ?? $item->categorie }}
                                    </p>
                                    <p class="text-sm text-gray-600">{{ $item->nombre }} transaction(s)</p>
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <p class="text-3xl font-bold text-pink-600">{{ number_format($item->total, 2) }} DH</p>
                                <p class="text-sm text-gray-500">
                                    Moyenne: {{ number_format($item->total / $item->nombre, 2) }} DH
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-inbox text-5xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Aucune dépense variable ce mois-ci</p>
                        </div>
                    @endforelse
                    
                    @if($rapportVariable->isNotEmpty())
                        <div class="mt-6 pt-6 border-t-2 border-pink-200">
                            <div class="flex justify-between items-center bg-pink-50 p-4 rounded-lg">
                                <p class="text-xl font-bold text-gray-800">TOTAL DÉPENSES VARIABLES</p>
                                <p class="text-3xl font-bold text-pink-700">{{ number_format($totalVariable, 2) }} DH</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Résumé Final --}}
            <div class="rapport-card">
                <div class="p-8 bg-gradient-to-br from-orange-400 to-pink-500 text-white text-center">
                    <i class="fas fa-coins text-6xl mb-4 opacity-80"></i>
                    <h3 class="text-3xl font-bold mb-2">Total des Dépenses du Mois</h3>
                    <p class="text-6xl font-bold mb-4">{{ number_format($totalGeneral, 2) }} DH</p>
                    <div class="flex justify-center gap-8 text-sm opacity-90">
                        <div>
                            <p class="font-semibold">Fixes</p>
                            <p class="text-2xl font-bold">{{ number_format($totalFixePaye, 2) }} DH</p>
                        </div>
                        <div>
                            <p class="font-semibold">Variables</p>
                            <p class="text-2xl font-bold">{{ number_format($totalVariable, 2) }} DH</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script>
        async function exportPDF() {
            const { jsPDF } = window.jspdf;
            
            Swal.fire({
                title: 'Génération du PDF...',
                html: 'Veuillez patienter',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Hide no-print elements
            document.querySelectorAll('.no-print').forEach(el => {
                el.style.display = 'none';
            });
            
            const content = document.querySelector('.py-8');
            
            try {
                const canvas = await html2canvas(content, {
                    scale: 2,
                    useCORS: true,
                    logging: false
                });
                
                const imgData = canvas.toDataURL('image/png');
                const pdf = new jsPDF('p', 'mm', 'a4');
                
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = pdf.internal.pageSize.getHeight();
                const imgWidth = canvas.width;
                const imgHeight = canvas.height;
                const ratio = Math.min(pdfWidth / imgWidth, pdfHeight / imgHeight);
                
                const imgX = (pdfWidth - imgWidth * ratio) / 2;
                const imgY = 0;
                
                pdf.addImage(imgData, 'PNG', imgX, imgY, imgWidth * ratio, imgHeight * ratio);
                pdf.save('rapport-depenses-{{ $mois }}.pdf');
                
                Swal.fire({
                    icon: 'success',
                    title: 'PDF Généré!',
                    text: 'Le rapport a été téléchargé avec succès',
                    timer: 2000,
                    showConfirmButton: false
                });
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Impossible de générer le PDF'
                });
            } finally {
                // Show no-print elements again
                document.querySelectorAll('.no-print').forEach(el => {
                    el.style.display = '';
                });
            }
        }
    </script>
    @endpush
</x-app-layout>