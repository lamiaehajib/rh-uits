
<style>
    .pagination {
        display: flex;
        padding-left: 0;
        list-style: none;
        border-radius: 0.375rem;
        justify-content: center;
    }
    
    .pagination .page-item .page-link {
        color: #6c757d;
        border: 1px solid #dee2e6;
        margin: 0 0.2rem;
        padding: 0.5rem 0.75rem;
        cursor: pointer;
        transition: all 0.3s ease, transform 0.2s ease;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #D32F2F;
        border-color: #C2185B;
        color: #fff;
        box-shadow: 0 4px 10px rgba(194, 69, 69, 0.3);
    }
    
    .pagination .page-item .page-link:hover {
        background-color: #be3030;
        border-color: #dee2e6;
        transform: translateY(-2px);
    }
    
    .pagination .page-item.disabled .page-link {
        color: #adb5bd;
        cursor: not-allowed;
    }
    
    .pagination .page-item .page-link:focus {
        outline: none;
        box-shadow: 0 0 8px rgba(162, 55, 55, 0.5);
    }
    </style>
    
    @if ($paginator->hasPages())
    <ul class="pagination justify-content-center">
        
        {{-- زر السابق --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
        @endif

        {{-- أرقام الصفحات --}}
        @php
            $totalPages = $paginator->lastPage();
            $currentPage = $paginator->currentPage();
        @endphp

        {{-- عرض الصفحة الأولى والثانية والثالثة --}}
        @for ($i = 1; $i <= 3 && $i <= $totalPages; $i++)
            <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        {{-- عرض `...` إذا كانت هناك فجوة بين الصفحات --}}
        @if ($currentPage > 5)
            <li class="page-item disabled"><span class="page-link">...</span></li>
        @endif

        {{-- عرض الصفحة الحالية إذا لم تكن في أول 3 صفحات --}}
        @if ($currentPage > 3 && $currentPage < $totalPages - 2)
            <li class="page-item active"><span class="page-link">{{ $currentPage }}</span></li>
        @endif

        {{-- عرض `...` قبل آخر 3 صفحات --}}
        @if ($currentPage < $totalPages - 4)
            <li class="page-item disabled"><span class="page-link">...</span></li>
        @endif

        {{-- عرض آخر 3 صفحات --}}
        @for ($i = max($totalPages - 2, 4); $i <= $totalPages; $i++)
            <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        {{-- زر التالي --}}
        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
        @else
            <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
        @endif
    </ul>
@endif


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const links = document.querySelectorAll('.page-link');
    
        links.forEach(link => {
            link.addEventListener('click', (e) => {
                const parentItem = e.target.closest('.page-item');
                if (parentItem && !parentItem.classList.contains('disabled')) {
                    parentItem.classList.add('clicked');
                    setTimeout(() => parentItem.classList.remove('clicked'), 300);
                }
            });
        });
    });
    </script>
        

