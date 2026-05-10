@extends('layouts.app')

@section('title', 'Kelola Kriteria SAW - SiLapor')
@section('header', 'Kelola Kriteria SAW')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <div>
        <p class="text-gray-600 font-semibold">Total bobot kriteria harus bernilai 1.0 (100%).</p>
    </div>
    <div class="text-xl font-bold bg-white px-4 py-2 border-2 border-neu-border rounded-lg shadow-[2px_2px_0px_rgba(0,0,0,1)]">
        Total: <span class="{{ abs($criteria->sum('weight') - 1.0) > 0.01 ? 'text-red-600' : 'text-green-600' }}">{{ $criteria->sum('weight') * 100 }}%</span>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @foreach($criteria as $c)
    <div class="neu-card p-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="text-xl font-bold">{{ $c->code }} - {{ $c->name }}</h3>
                <span class="inline-block mt-2 text-xs font-bold px-2 py-1 border-2 border-neu-border rounded {{ $c->type == 'benefit' ? 'bg-green-200' : 'bg-red-200' }}">
                    Type: {{ strtoupper($c->type) }}
                </span>
            </div>
            <div class="text-3xl font-black text-gray-300">{{ $c->code }}</div>
        </div>
        
        <form action="{{ route('admin.saw.criteria.update', $c) }}" method="POST" class="mt-4 border-t-2 border-neu-border pt-4">
            @csrf
            @method('PUT')
            <div class="flex items-end gap-4">
                <div class="flex-1">
                    <label class="block font-bold mb-1" for="weight_{{ $c->id }}">Bobot (0.01 - 1.0)</label>
                    <input type="number" step="0.01" min="0" max="1" id="weight_{{ $c->id }}" name="weight" value="{{ $c->weight }}" required class="neu-input w-full font-mono text-lg">
                </div>
                <button type="submit" class="neu-btn-primary">Update</button>
            </div>
        </form>
    </div>
    @endforeach
</div>
@endsection
