@extends('layouts.dashboard')

@section('title', 'الدورات التدريبية')

@section('header', 'إدارة الدورات التدريبية')

@section('content')
<div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">قائمة الدورات التدريبية</h2>
            <a href="{{ route('courses.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                إضافة دورة جديدة
            </a>
        </div>

        <!-- Search and Filter -->
        <div class="mb-6">
            <form action="{{ route('courses.index') }}" method="GET" class="flex gap-4">
                <div class="flex-1">
                    <input type="text" name="search" placeholder="البحث عن دورة..." 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                           value="{{ request('search') }}">
                </div>
                <div class="w-48">
                    <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">كل الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>قادم</option>
                    </select>
                </div>
                <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    بحث
                </button>
            </form>
        </div>

        <!-- Courses Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($courses ?? [] as $course)
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="relative">
                    <img class="w-full h-48 object-cover rounded-t-lg" src="{{ $course->image_url ?? asset('images/default-course.jpg') }}" alt="{{ $course->title }}">
                    <div class="absolute top-2 left-2">
                        @switch($course->status ?? 'active')
                            @case('active')
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">نشط</span>
                                @break
                            @case('completed')
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">مكتمل</span>
                                @break
                            @case('upcoming')
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">قادم</span>
                                @break
                        @endswitch
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $course->title }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{{ Str::limit($course->description, 100) }}</p>
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $course->duration ?? '0' }} ساعة
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            {{ $course->enrolled_count ?? '0' }} متدرب
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="space-x-2 space-x-reverse">
                            <a href="{{ route('courses.show', $course) }}" class="text-blue-600 hover:text-blue-900">عرض</a>
                            <a href="{{ route('courses.edit', $course) }}" class="text-yellow-600 hover:text-yellow-900">تعديل</a>
                            <form action="{{ route('courses.destroy', $course) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('هل أنت متأكد من حذف هذه الدورة؟')">
                                    حذف
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12 text-gray-500">
                لا توجد دورات تدريبية حالياً
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if(isset($courses) && $courses->hasPages())
        <div class="mt-6">
            {{ $courses->links() }}
        </div>
        @endif
    </div>
</div>
@endsection 