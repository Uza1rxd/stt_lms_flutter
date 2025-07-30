import 'package:flutter/foundation.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:ready_lms/model/common/common_response_model.dart';
import 'package:ready_lms/model/live_class.dart';
import 'package:ready_lms/service/live_class_service.dart';

class LiveClassController extends StateNotifier<AsyncValue<List<LiveClass>>> {
  final Ref ref;
  LiveClassController(this.ref) : super(const AsyncValue.loading());

  List<LiveClass> get liveClasses => state.value ?? [];
  List<LiveClass> get activeLiveClasses => 
      liveClasses.where((cls) => cls.isLive).toList();
  List<LiveClass> get upcomingClasses => 
      liveClasses.where((cls) => cls.isUpcoming).toList();

  Future<void> fetchLiveClasses() async {
    try {
      state = const AsyncValue.loading();
      
      final response = await ref.read(liveClassServiceProvider).getLiveClasses();
      
      if (response.statusCode == 200) {
        final List<dynamic> classesData = response.data['data']['live_classes'] ?? [];
        final List<LiveClass> classes = classesData
            .map((classData) => LiveClass.fromJson(classData))
            .toList();
        
        // Sort by start time, with live classes first
        classes.sort((a, b) {
          if (a.isLive && !b.isLive) return -1;
          if (!a.isLive && b.isLive) return 1;
          return a.startTime.compareTo(b.startTime);
        });
        
        state = AsyncValue.data(classes);
      } else {
        state = AsyncValue.error(
          'Failed to load live classes', 
          StackTrace.current
        );
      }
    } catch (error, stackTrace) {
      debugPrint('Live classes fetch error: $error');
      state = AsyncValue.error(error, stackTrace);
    }
  }

  Future<CommonResponse> joinClass(LiveClass liveClass) async {
    try {
      final response = await ref
          .read(liveClassServiceProvider)
          .joinLiveClass(classId: liveClass.id);
      
      if (response.statusCode == 200) {
        return CommonResponse(
          isSuccess: true,
          message: 'Joining class...',
          response: liveClass.zoomLink,
        );
      } else {
        return CommonResponse(
          isSuccess: false,
          message: response.data['message'] ?? 'Failed to join class',
        );
      }
    } catch (error) {
      debugPrint('Join class error: $error');
      return CommonResponse(
        isSuccess: false,
        message: error.toString(),
      );
    }
  }

  // Mock data for development/testing
  void loadMockData() {
    final mockClasses = [
      LiveClass(
        id: 1,
        title: 'Advanced Flutter Development',
        description: 'Learn advanced Flutter concepts and state management',
        instructorName: 'Dr. Sarah Johnson',
        instructorImage: 'assets/images/im_demo_user_1.png',
        zoomLink: 'https://zoom.us/j/1234567890',
        meetingId: '123 456 7890',
        passcode: 'flutter',
        startTime: DateTime.now().subtract(const Duration(minutes: 5)),
        endTime: DateTime.now().add(const Duration(hours: 1)),
        status: 'live',
        thumbnail: 'assets/images/im_course_demo.png',
        participantCount: 45,
        maxParticipants: 100,
        category: 'Programming',
      ),
      LiveClass(
        id: 2,
        title: 'UI/UX Design Principles',
        description: 'Master the fundamentals of user interface design',
        instructorName: 'Mike Chen',
        instructorImage: 'assets/images/im_demo_user_1.png',
        zoomLink: 'https://zoom.us/j/0987654321',
        meetingId: '098 765 4321',
        passcode: 'design',
        startTime: DateTime.now().add(const Duration(hours: 2)),
        endTime: DateTime.now().add(const Duration(hours: 3)),
        status: 'upcoming',
        thumbnail: 'assets/images/im_course_demo.png',
        participantCount: 0,
        maxParticipants: 50,
        category: 'Design',
      ),
      LiveClass(
        id: 3,
        title: 'Mobile App Security',
        description: 'Best practices for securing mobile applications',
        instructorName: 'Alex Rodriguez',
        instructorImage: 'assets/images/im_demo_user_1.png',
        zoomLink: 'https://zoom.us/j/1122334455',
        meetingId: '112 233 4455',
        passcode: 'security',
        startTime: DateTime.now().add(const Duration(hours: 4)),
        endTime: DateTime.now().add(const Duration(hours: 5)),
        status: 'upcoming',
        thumbnail: 'assets/images/im_course_demo.png',
        participantCount: 0,
        maxParticipants: 75,
        category: 'Security',
      ),
    ];
    
    state = AsyncValue.data(mockClasses);
  }
}

final liveClassController = StateNotifierProvider<LiveClassController, AsyncValue<List<LiveClass>>>(
  (ref) => LiveClassController(ref),
);