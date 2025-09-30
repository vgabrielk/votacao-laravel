@extends('layouts.app')

@section('title', 'Grupos')
@section('page-title', 'Grupos')

@section('content')
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 lg:mb-8">
        <div class="mb-4 lg:mb-0">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Meus Grupos</h1>
            <p class="text-gray-600 mt-2">Gerencie seus grupos e participe de comunidades</p>
        </div>
        <div class="mt-4 lg:mt-0">
            <x-button href="{{ route('groups.create') }}" variant="primary" size="lg" icon="ri-add-line">
                Criar Novo Grupo
            </x-button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <x-stats-card 
            title="Total de Grupos" 
            :value="$groups->count()" 
            icon="ri-group-line" 
            gradient="from-blue-500 to-blue-600" 
            text-color="text-blue-100" 
        />
        
        <x-stats-card 
            title="Membros Totais" 
            :value="$groups->sum('members_count') ?? 0" 
            icon="ri-user-line" 
            gradient="from-green-500 to-green-600" 
            text-color="text-green-100" 
        />
        
        <x-stats-card 
            title="Grupos Criados" 
            :value="$groups->where('creator_id', Auth::id())->count()" 
            icon="ri-crown-line" 
            gradient="from-purple-500 to-purple-600" 
            text-color="text-purple-100" 
        />
    </div>

    <!-- Groups Table -->
    @if($groups->count() > 0)
    <x-card>
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Todos os Grupos</h2>
        </div>
        
        <!-- Table with horizontal scroll container - SOLUÇÃO DEFINITIVA -->
        <div style="overflow-x: auto; width: 100%; max-width: 100%;">
            <table style="width: 100%; min-width: 1000px; border-collapse: collapse;">
                <thead style="background-color: #f9fafb;">
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <th style="text-align: left; padding: 16px 24px; font-weight: 500; color: #374151; white-space: nowrap; min-width: 200px;">
                            <button style="display: flex; align-items: center; gap: 4px; cursor: pointer; background: none; border: none;">
                                <span>Grupo</span>
                                <i class="ri-arrow-up-down-line" style="font-size: 12px;"></i>
                            </button>
                        </th>
                        <th style="text-align: left; padding: 16px 24px; font-weight: 500; color: #374151; white-space: nowrap; min-width: 150px;">Criador</th>
                        <th style="text-align: left; padding: 16px 24px; font-weight: 500; color: #374151; white-space: nowrap; min-width: 80px;">Membros</th>
                        <th style="text-align: left; padding: 16px 24px; font-weight: 500; color: #374151; white-space: nowrap; min-width: 120px;">Visibilidade</th>
                        <th style="text-align: left; padding: 16px 24px; font-weight: 500; color: #374151; white-space: nowrap; min-width: 100px;">Criado em</th>
                        <th style="text-align: left; padding: 16px 24px; font-weight: 500; color: #374151; white-space: nowrap; min-width: 100px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groups as $group)
                        <tr style="border-bottom: 1px solid #f3f4f6; transition: background-color 0.2s; cursor: pointer;" 
                            onmouseover="this.style.backgroundColor='#f9fafb'" 
                            onmouseout="this.style.backgroundColor='transparent'"
                            onclick="window.location.href='{{ route('groups.show', $group) }}'">
                            <td style="padding: 16px 24px; white-space: nowrap;">
                                <span class="text-gray-900 font-medium">{{ $group->name }}</span>
                            </td>
                            <td style="padding: 16px 24px; white-space: nowrap;">{{ $group->creator->name }}</td>
                            <td style="padding: 16px 24px; white-space: nowrap;">{{ $group->members()->count() }}</td>
                            <td style="padding: 16px 24px; white-space: nowrap;"><x-status-badge :status="$group->visibility" /></td>
                            <td style="padding: 16px 24px; white-space: nowrap;">{{ $group->created_at->format('d/m/Y') }}</td>
                            <td style="padding: 16px 24px; white-space: nowrap;">
                                <x-button href="{{ route('groups.show', $group) }}" variant="primary" size="sm" icon="ri-eye-line" onclick="event.stopPropagation()">Ver</x-button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <x-pagination :paginator="$groups" />
    </x-card>
    @endif

    <!-- Empty State -->
    @if($groups->count() === 0)
    <div class="text-center py-12">
        <div class="w-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <i class="ri-group-line text-2xl text-gray-400"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Nenhum grupo ainda</h3>
        <p class="text-gray-600 mb-6">Crie seu primeiro grupo e comece a conectar pessoas!</p>
        <a href="{{ route('groups.create') }}" class="inline-flex items-center justify-center font-medium rounded-2xl transition-colors whitespace-nowrap cursor-pointer bg-purple-600 text-white hover:bg-purple-700 px-6 py-3 text-sm">
            <i class="ri-add-line mr-2"></i>
            Criar Primeiro Grupo
        </a>
    </div>
    @endif
    </div>

    <script>
        function confirmDeleteGroup(groupId, groupName) {
            openConfirmationModal(
                'Deletar Grupo',
                `Tem certeza que deseja deletar o grupo "${groupName}"? Esta ação é permanente e não pode ser desfeita.`,
                'Deletar',
                function() {
                    // Create and submit form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/groups/${groupId}`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    
                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            );
        }
    </script>
@endsection
