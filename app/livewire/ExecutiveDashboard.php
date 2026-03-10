<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Placement;
use App\Models\Application;
use App\Models\Partner;
use App\Models\User; // أضفنا موديل المستخدم هنا
use Carbon\Carbon;

class ExecutiveDashboard extends Component
{
    public function render()
    {
        // ==========================================
        // 1. الإحصائيات الإجمالية للوكالة (All-Time)
        // ==========================================
        $allPlacements = Placement::all();
        $agencyTotalRevenue = $allPlacements->sum('total_agency_fee');
        $agencyNetProfit = $allPlacements->sum('net_profit');
        $totalPartnerFees = $allPlacements->sum('partner_commission');
        $totalPlacements = $allPlacements->count();

        // ==========================================
        // 2. إحصائيات الشهر الحالي (Monthly KPIs)
        // ==========================================
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $lastMonthDate = Carbon::now()->subMonth();
        
        $profitThisMonth = Placement::whereMonth('created_at', $currentMonth)
                                    ->whereYear('created_at', $currentYear)
                                    ->sum('net_profit');
                                    
        $profitLastMonth = Placement::whereMonth('created_at', $lastMonthDate->month)
                                    ->whereYear('created_at', $lastMonthDate->year)
                                    ->sum('net_profit');

        $profitChange = 0;
        if ($profitLastMonth > 0) {
            $profitChange = (($profitThisMonth - $profitLastMonth) / $profitLastMonth) * 100;
        } elseif ($profitThisMonth > 0) {
            $profitChange = 100; 
        }

        $activeCandidatesCount = Application::whereNotIn('status', ['accepted', 'rejected'])->count();

        $topPartner = Partner::withCount('candidates')
            ->having('candidates_count', '>', 0)
            ->orderBy('candidates_count', 'desc')
            ->first();

        // ==========================================
        // 3. أداء فريق التوظيف (Leaderboard)
        // ==========================================
        $recruiters = User::all()->map(function($user) {
            $userPlacements = Placement::whereHas('application.project', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();

            $user->total_sales = $userPlacements->sum('net_profit');
            $user->total_commission = $userPlacements->sum('recruiter_commission');
            $user->placements_count = $userPlacements->count();
            return $user;
        });

        // ==========================================
        // 4. المهام التشغيلية (اليوم)
        // ==========================================
        $todaysInterviews = Application::with(['candidate', 'project.client'])
            ->whereNotNull('interview_date')
            ->whereDate('interview_date', Carbon::today())
            ->orderBy('interview_time', 'asc')
            ->get();

        // ==========================================
        // 5. بيانات الرسوم البيانية (Charts)
        // ==========================================
        $chartLabels = [];
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $chartLabels[] = $month->translatedFormat('F');
            $chartData[] = Placement::whereMonth('created_at', $month->month)
                                    ->whereYear('created_at', $month->year)
                                    ->sum('net_profit');
        }

        $allApps = Application::all();
        $totalReceived = $allApps->count();
        $shortlistedPlus = $allApps->whereIn('status', ['shortlisted', 'sent_to_client', 'interviewing', 'accepted'])->count();
        $sentPlus = $allApps->whereIn('status', ['sent_to_client', 'interviewing', 'accepted'])->count();
        $interviewPlus = $allApps->whereIn('status', ['interviewing', 'accepted'])->count();
        $hired = $allApps->where('status', 'accepted')->count();

        $funnelData = [
            ['x' => 'استلام', 'y' => $totalReceived],
            ['x' => 'فرز مبدئي', 'y' => $shortlistedPlus],
            ['x' => 'إرسال للعميل', 'y' => $sentPlus],
            ['x' => 'مقابلات', 'y' => $interviewPlus],
            ['x' => 'توظيف', 'y' => $hired],
        ];

        // ==========================================
        // 6. التنبيهات الذكية (Smart AI Alerts)
        // ==========================================
        $smartAlerts = collect();

        // التنبيه الأول: تأخر رد العملاء (أكثر من 3 أيام)
        $delayedFeedbackCount = Application::where('status', 'sent_to_client')
            ->where('updated_at', '<', Carbon::now()->subDays(3))
            ->count();
            
        if ($delayedFeedbackCount > 0) {
            $smartAlerts->push([
                'icon' => '⏳',
                'title' => 'تأخر رد العملاء',
                'message' => "يوجد {$delayedFeedbackCount} مرشحين بانتظار رد العملاء منذ أكثر من 3 أيام. يرجى التواصل مع المستشفيات.",
                'bgColor' => 'bg-amber-50',
                'textColor' => 'text-amber-800',
                'borderColor' => 'border-amber-200',
                'iconColor' => 'text-amber-600',
                'link' => '/' // يمكنك تغييره لاحقاً لصفحة معينة
            ]);
        }

        // التنبيه الثاني: مرشحون في الفرز المبدئي نسينا إرسالهم
        $unsentShortlisted = Application::where('status', 'shortlisted')->count();
        
        if ($unsentShortlisted > 0) {
            $smartAlerts->push([
                'icon' => '🚀',
                'title' => 'مرشحون جاهزون للإرسال',
                'message' => "يوجد {$unsentShortlisted} مرشحين اجتازوا الفرز المبدئي وهم جاهزون للإرسال للعملاء الآن.",
                'bgColor' => 'bg-blue-50',
                'textColor' => 'text-blue-800',
                'borderColor' => 'border-blue-200',
                'iconColor' => 'text-blue-600',
                'link' => '/'
            ]);
        }

        // التنبيه الثالث: أموال غير محصلة
        $pendingInvoicesCount = Placement::where('payment_status', 'pending')->count();
        
        if ($pendingInvoicesCount > 0) {
            $smartAlerts->push([
                'icon' => '💰',
                'title' => 'مبالغ قيد التحصيل',
                'message' => "يوجد {$pendingInvoicesCount} مطالبات مالية معلقة لم يتم استلامها بالكامل. يرجى مراجعة الخزينة.",
                'bgColor' => 'bg-rose-50',
                'textColor' => 'text-rose-800',
                'borderColor' => 'border-rose-200',
                'iconColor' => 'text-rose-600',
                'link' => '/collections' // يوجه لشاشة التحصيلات
            ]);
        }

        return view('components.executive-dashboard', [
            'agencyTotalRevenue' => $agencyTotalRevenue,
            'agencyNetProfit' => $agencyNetProfit,
            'totalPartnerFees' => $totalPartnerFees,
            'totalPlacements' => $totalPlacements,
            'profitThisMonth' => $profitThisMonth,
            'profitChange' => $profitChange,
            'activeCandidatesCount' => $activeCandidatesCount,
            'topPartner' => $topPartner,
            'recruiters' => $recruiters,
            'todaysInterviews' => $todaysInterviews,
            'chartLabels' => json_encode($chartLabels),
            'chartData' => json_encode($chartData),
            'funnelData' => json_encode($funnelData),
            'smartAlerts' => $smartAlerts, // <--- السطر الجديد
        ])->layout('layouts.app', ['title' => 'لوحة القيادة الشاملة']);
    }
}