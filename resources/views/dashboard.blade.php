@extends('layouts.dashboard')

@section('title', 'لوحة التحكم')

@section('header', 'لوحة التحكم')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Total Employees Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="mr-4">
                    <div class="text-sm font-medium text-gray-500">إجمالي الموظفين</div>
                    <div class="text-2xl font-semibold text-gray-900">{{ $totalEmployees ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Courses Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div class="mr-4">
                    <div class="text-sm font-medium text-gray-500">الدورات النشطة</div>
                    <div class="text-2xl font-semibold text-gray-900">{{ $activeCourses ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Departments Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div class="mr-4">
                    <div class="text-sm font-medium text-gray-500">الأقسام</div>
                    <div class="text-2xl font-semibold text-gray-900">{{ $departments ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Completed Courses Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="mr-4">
                    <div class="text-sm font-medium text-gray-500">الدورات المكتملة</div>
                    <div class="text-2xl font-semibold text-gray-900">{{ $completedCourses ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="mt-8">
    <h2 class="text-lg font-medium text-gray-900 mb-4">النشاطات الأخيرة</h2>
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="divide-y divide-gray-200">
            @forelse($recentActivities ?? [] as $activity)
            <div class="p-4 hover:bg-gray-50">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100">
                            <span class="text-sm font-medium leading-none text-blue-600">{{ $activity->user_initials ?? 'U' }}</span>
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ $activity->description ?? 'نشاط جديد' }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $activity->created_at ?? now() }}
                        </p>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-4 text-center text-gray-500">
                لا توجد نشاطات حديثة
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection 