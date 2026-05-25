<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SavedTimetable;
use App\Models\DraftTimetable;

class DraftTimetableController extends Controller
{
    // Display the timetable page
    public function index()
    {
        return view('pages.draft.index');
    }
    
    // Generate timetable from user's subjects
    public function generate(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'level' => 'nullable|string',
            'subjects' => 'required|array|min:1|max:10',
        ]);
        
        $subjects = $request->subjects;
        $name = $request->name ?? 'Student';
        $level = $request->level ?? '100 Level';
        
        // Search for courses in the timetables table
        $foundCourses = [];
        $missingCourses = [];
        
        foreach ($subjects as $subject) {
            // Search by course_code (case insensitive)
            $course = DraftTimetable::where(DB::raw('UPPER(course_code)'), 'LIKE', '%' . strtoupper($subject) . '%')
                ->where('status', 'active')
                ->first();
            
            if ($course) {
                $foundCourses[] = [
                    'course_code' => $course->course_code,
                    'course_title' => $course->course_title,
                    'exam_date' => $course->exam_date,
                    'time_slot' => $course->time_slot,
                    'type_of_time_table' => $course->type_of_time_table
                ];
            } else {
                $missingCourses[] = $subject;
            }
        }
        
        // Generate timetable HTML
        $timetableHTML = $this->generateTimetableHTML($name, $level, $foundCourses);
        
        // Save to saved_timetables
        $sessionId = session()->getId();
        $ipAddress = $request->ip();
        
        $existing = SavedTimetable::where('session_id', $sessionId)
            ->orWhere('ip_address', $ipAddress)
            ->first();
        
        if ($existing) {
            $existing->update([
                'name' => $name,
                'level' => $level,
                'subjects' => $subjects,
                'timetable_data' => [
                    'html' => $timetableHTML,
                    'found_courses' => $foundCourses,
                    'missing_courses' => $missingCourses
                ]
            ]);
        } else {
            SavedTimetable::create([
                'session_id' => $sessionId,
                'ip_address' => $ipAddress,
                'name' => $name,
                'level' => $level,
                'subjects' => $subjects,
                'timetable_data' => [
                    'html' => $timetableHTML,
                    'found_courses' => $foundCourses,
                    'missing_courses' => $missingCourses
                ]
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Timetable generated successfully!',
            'timetable_html' => $timetableHTML,
            'found_courses' => $foundCourses,
            'missing_courses' => $missingCourses,
            'total_found' => count($foundCourses),
            'total_missing' => count($missingCourses)
        ]);
    }
    
    // Generate Timetable HTML
    private function generateTimetableHTML($name, $level, $courses)
    {
        if (empty($courses)) {
            return '<div class="no-results"><div style="font-size: 3rem;">📭</div><p>No courses found in our database. Please check your course codes.</p></div>';
        }
        
        $html = '
        <thead>
            <tr>
                <th colspan="5" style="text-align: center; background: #1A6B3C; color: white; padding: 16px; font-size: 1rem;">
                    📚DRAFT EXAM TIMETABLE FOR ' . strtoupper(htmlspecialchars($name)) . ' (' . htmlspecialchars($level) . ')
                </th>
            </tr>
            <tr style="background: #e8f0e8;">
                <th style="padding: 12px;">Course Code</th>
                <th style="padding: 12px;">Course Title</th>
                <th style="padding: 12px;">Exam Date</th>
                <th style="padding: 12px;">Time Slot</th>
                <th style="padding: 12px;">Exam Type</th>
            </tr>
        </thead>
        <tbody>';
        
        foreach ($courses as $course) {
            $html .= '
            <tr>
                <td style="padding: 12px;"><strong>' . htmlspecialchars($course['course_code']) . '</strong></td>
                <td style="padding: 12px;">' . htmlspecialchars($course['course_title']) . '</td>
                <td style="padding: 12px;">' . date('l, F j, Y', strtotime($course['exam_date'])) . '</td>
                <td style="padding: 12px;">' . htmlspecialchars($course['time_slot']) . '</td>
                <td style="padding: 12px;"><span class="badge badge-success">' . htmlspecialchars($course['type_of_time_table']) . '</span></td>
            </tr>';
        }
        
        $html .= '
        <tr style="background: #e8f0e8;">
            <td colspan="5" style="padding: 16px; text-align: center; font-size: 0.8rem; color: #1A6B3C;">
                💡 Exam Tips: Arrive 30 minutes early | Bring your ID card | No phones allowed
            </td>
        </tr>';
        
        $html .= '</tbody>';
        
        return $html;
    }
    
    // Get course suggestions (for autocomplete)
    public function suggestCourses(Request $request)
    {
        $query = $request->get('q', '');
        
        $courses = DraftTimetable::where('course_code', 'LIKE', "%{$query}%")
            ->orWhere('course_title', 'LIKE', "%{$query}%")
            ->where('status', 'active')
            ->limit(10)
            ->get(['course_code', 'course_title']);
        
        return response()->json($courses);
    }
    
    // Get all available courses
    public function getAllCourses()
    {
        $courses = DraftTimetable::where('status', 'active')
            ->orderBy('course_code')
            ->get(['course_code', 'course_title', 'exam_date', 'time_slot', 'type_of_time_table']);
        
        return response()->json([
            'success' => true,
            'courses' => $courses,
            'count' => $courses->count()
        ]);
    }
    
    // Save timetable data
    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string',
            'subjects' => 'required|array|min:1|max:10',
            'timetable_html' => 'nullable|string'
        ]);
        
        $sessionId = session()->getId();
        $ipAddress = $request->ip();
        
        $existing = SavedTimetable::where('session_id', $sessionId)
            ->orWhere('ip_address', $ipAddress)
            ->first();
        
        if ($existing) {
            $existing->update([
                'name' => $request->name,
                'level' => $request->level,
                'subjects' => $request->subjects,
                'timetable_data' => ['html' => $request->timetable_html]
            ]);
            $timetable = $existing;
        } else {
            $timetable = SavedTimetable::create([
                'session_id' => $sessionId,
                'ip_address' => $ipAddress,
                'name' => $request->name,
                'level' => $request->level,
                'subjects' => $request->subjects,
                'timetable_data' => ['html' => $request->timetable_html]
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Timetable saved successfully!',
            'id' => $timetable->id
        ]);
    }
    
    // Load saved timetable
    public function load(Request $request)
    {
        $sessionId = session()->getId();
        $ipAddress = $request->ip();
        
        $timetable = SavedTimetable::where('session_id', $sessionId)
            ->orWhere('ip_address', $ipAddress)
            ->first();
        
        if ($timetable) {
            return response()->json([
                'success' => true,
                'data' => [
                    'name' => $timetable->name,
                    'level' => $timetable->level,
                    'subjects' => $timetable->subjects,
                    'timetable_html' => $timetable->timetable_data['html'] ?? null
                ]
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'No saved timetable found'
        ]);
    }
    
    // Delete timetable
    public function delete($id)
    {
        $timetable = SavedTimetable::findOrFail($id);
        $timetable->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Timetable deleted successfully'
        ]);
    }
    
    // Admin view all timetables
    public function adminIndex()
    {
        $timetables = SavedTimetable::latest()->paginate(20);
        return view('admin.timetables', compact('timetables'));
    }
}