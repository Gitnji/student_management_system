<aside
    id="sidebar"
    class="fixed inset-y-0 left-0 z-30 w-64 bg-navy flex flex-col transform -translate-x-full
           transition-transform duration-200 ease-in-out lg:relative lg:translate-x-0 lg:flex"
>
    {{-- Brand --}}
    <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
        <div class="w-9 h-9 rounded-lg bg-royal flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
            </svg>
        </div>
        <div class="overflow-hidden">
            <p class="text-white font-bold text-sm leading-tight truncate">ICC</p>
            <p class="text-sky-custom text-xs leading-tight">School Management</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3">

        @if(auth()->user()->isAdmin())
            {{-- Admin Navigation --}}
            <div class="mb-2">
                <p class="text-white/30 text-xs font-semibold uppercase tracking-wider px-3 mb-2">Main</p>

                <x-nav-link route="admin.dashboard" icon="home">
                    Dashboard
                </x-nav-link>
            </div>

            <div class="mb-2">
                <p class="text-white/30 text-xs font-semibold uppercase tracking-wider px-3 mb-2 mt-4">Academic</p>

                <x-nav-link route="admin.academic-years.index" icon="calendar">
                    Academic Years
                </x-nav-link>
                <x-nav-link route="admin.classrooms.index" icon="building">
                    Classrooms
                </x-nav-link>
                <x-nav-link route="admin.subjects.index" icon="book">
                    Subjects
                </x-nav-link>
                <x-nav-link route="admin.students.index" icon="users">
                    Students
                </x-nav-link>
                <x-nav-link route="admin.teachers.index" icon="user-group">
                    Teachers
                </x-nav-link>
                <x-nav-link route="admin.teacher-assignments.index" icon="user-group">
                     Assignments
                </x-nav-link>
            </div>

            <div class="mb-2">
                <p class="text-white/30 text-xs font-semibold uppercase tracking-wider px-3 mb-2 mt-4">Results</p>

                <x-nav-link route="admin.sequences.index" icon="clipboard">
                    Sequences
                </x-nav-link>
                <x-nav-link route="admin.report-cards.index" icon="document">
                    Report Cards
                </x-nav-link>
                <x-nav-link route="admin.promotions.index" icon="star">
                    Promotions
                </x-nav-link>
            </div>

            <div class="mb-2">
                <p class="text-white/30 text-xs font-semibold uppercase tracking-wider px-3 mb-2 mt-4">Website</p>

                <x-nav-link route="admin.cms.pages.index" icon="globe">
                    Pages
                </x-nav-link>
                <x-nav-link route="admin.cms.blog.index" icon="pencil">
                    Blog Posts
                </x-nav-link>
                <x-nav-link route="admin.cms.events.index" icon="star">
                    Events
                </x-nav-link>
                <x-nav-link route="admin.cms.gallery.index" icon="photo">
                    Gallery
                </x-nav-link>
            </div>

        @else
            {{-- Teacher Navigation --}}
            <div class="mb-2">
                <p class="text-white/30 text-xs font-semibold uppercase tracking-wider px-3 mb-2">Main</p>

                <x-nav-link route="teacher.dashboard" icon="home">
                    Dashboard
                </x-nav-link>
            </div>

            <div class="mb-2">
                <p class="text-white/30 text-xs font-semibold uppercase tracking-wider px-3 mb-2 mt-4">My Classes</p>

                <x-nav-link route="teacher.marks.index" icon="clipboard">
                    Enter Marks
                </x-nav-link>
                <x-nav-link route="teacher.classes.index" icon="building">
                    My Classes
                </x-nav-link>
            </div>
        @endif

    </nav>

    {{-- User + Logout --}}
    <div class="border-t border-white/10 p-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-royal flex items-center justify-center flex-shrink-0">
                <span class="text-white text-xs font-bold">
                    {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}
                </span>
            </div>
            <div class="flex-1 overflow-hidden">
                <p class="text-white text-sm font-medium truncate">{{ auth()->user()->full_name }}</p>
                <p class="text-white/40 text-xs truncate capitalize">{{ auth()->user()->role }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Logout"
                    class="text-white/40 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>