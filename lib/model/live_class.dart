class LiveClass {
  final int id;
  final String title;
  final String description;
  final String instructorName;
  final String instructorImage;
  final String zoomLink;
  final String meetingId;
  final String passcode;
  final DateTime startTime;
  final DateTime endTime;
  final String status; // 'live', 'upcoming', 'ended'
  final String thumbnail;
  final int participantCount;
  final int maxParticipants;
  final String category;

  LiveClass({
    required this.id,
    required this.title,
    required this.description,
    required this.instructorName,
    required this.instructorImage,
    required this.zoomLink,
    required this.meetingId,
    required this.passcode,
    required this.startTime,
    required this.endTime,
    required this.status,
    required this.thumbnail,
    required this.participantCount,
    required this.maxParticipants,
    required this.category,
  });

  factory LiveClass.fromJson(Map<String, dynamic> json) {
    return LiveClass(
      id: json['id'] ?? 0,
      title: json['title'] ?? '',
      description: json['description'] ?? '',
      instructorName: json['instructor_name'] ?? '',
      instructorImage: json['instructor_image'] ?? '',
      zoomLink: json['zoom_link'] ?? '',
      meetingId: json['meeting_id'] ?? '',
      passcode: json['passcode'] ?? '',
      startTime: DateTime.parse(json['start_time'] ?? DateTime.now().toIso8601String()),
      endTime: DateTime.parse(json['end_time'] ?? DateTime.now().toIso8601String()),
      status: json['status'] ?? 'upcoming',
      thumbnail: json['thumbnail'] ?? '',
      participantCount: json['participant_count'] ?? 0,
      maxParticipants: json['max_participants'] ?? 0,
      category: json['category'] ?? '',
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'title': title,
      'description': description,
      'instructor_name': instructorName,
      'instructor_image': instructorImage,
      'zoom_link': zoomLink,
      'meeting_id': meetingId,
      'passcode': passcode,
      'start_time': startTime.toIso8601String(),
      'end_time': endTime.toIso8601String(),
      'status': status,
      'thumbnail': thumbnail,
      'participant_count': participantCount,
      'max_participants': maxParticipants,
      'category': category,
    };
  }

  bool get isLive => status == 'live';
  bool get isUpcoming => status == 'upcoming';
  bool get hasEnded => status == 'ended';

  String get timeRemaining {
    if (isLive) return 'Live Now';
    
    final now = DateTime.now();
    final difference = startTime.difference(now);
    
    if (difference.isNegative) return 'Started';
    
    if (difference.inDays > 0) {
      return '${difference.inDays}d ${difference.inHours % 24}h';
    } else if (difference.inHours > 0) {
      return '${difference.inHours}h ${difference.inMinutes % 60}m';
    } else {
      return '${difference.inMinutes}m';
    }
  }

  String get formattedTime {
    final hour = startTime.hour;
    final minute = startTime.minute.toString().padLeft(2, '0');
    final period = hour >= 12 ? 'PM' : 'AM';
    final displayHour = hour > 12 ? hour - 12 : (hour == 0 ? 12 : hour);
    return '$displayHour:$minute $period';
  }
}