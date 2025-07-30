import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:ready_lms/config/theme.dart';
import 'package:ready_lms/controllers/live_class.dart';
import 'package:ready_lms/model/live_class.dart';
import 'package:ready_lms/utils/entensions.dart';
import 'package:ready_lms/utils/global_function.dart';
import 'package:ready_lms/generated/l10n.dart';
import 'package:url_launcher/url_launcher.dart';

class LiveClassesSection extends ConsumerWidget {
  const LiveClassesSection({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final liveClassesAsync = ref.watch(liveClassController);
    
    return liveClassesAsync.when(
      data: (classes) {
        final liveClasses = classes.where((cls) => cls.isLive).toList();
        final upcomingClasses = classes.where((cls) => cls.isUpcoming).take(3).toList();
        
        if (liveClasses.isEmpty && upcomingClasses.isEmpty) {
          return const SizedBox.shrink();
        }
        
        return Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            if (liveClasses.isNotEmpty) ...[
              _SectionHeader(
                title: 'Live Classes',
                subtitle: '${liveClasses.length} classes ongoing',
                isLive: true,
              ),
              16.ph,
              SizedBox(
                height: 200.h,
                child: ListView.builder(
                  scrollDirection: Axis.horizontal,
                  padding: EdgeInsets.symmetric(horizontal: 16.w),
                  itemCount: liveClasses.length,
                  itemBuilder: (context, index) {
                    return Padding(
                      padding: EdgeInsets.only(right: 12.w),
                      child: LiveClassCard(
                        liveClass: liveClasses[index],
                        onTap: () => _joinLiveClass(context, ref, liveClasses[index]),
                      ),
                    );
                  },
                ),
              ),
              20.ph,
            ],
            if (upcomingClasses.isNotEmpty) ...[
              _SectionHeader(
                title: 'Upcoming Classes',
                subtitle: 'Next ${upcomingClasses.length} classes',
                isLive: false,
              ),
              16.ph,
              SizedBox(
                height: 200.h,
                child: ListView.builder(
                  scrollDirection: Axis.horizontal,
                  padding: EdgeInsets.symmetric(horizontal: 16.w),
                  itemCount: upcomingClasses.length,
                  itemBuilder: (context, index) {
                    return Padding(
                      padding: EdgeInsets.only(right: 12.w),
                      child: LiveClassCard(
                        liveClass: upcomingClasses[index],
                        onTap: () => _showClassDetails(context, upcomingClasses[index]),
                      ),
                    );
                  },
                ),
              ),
              20.ph,
            ],
          ],
        );
      },
      loading: () => const SizedBox.shrink(),
      error: (error, stack) => const SizedBox.shrink(),
    );
  }

  void _joinLiveClass(BuildContext context, WidgetRef ref, LiveClass liveClass) async {
    try {
      final result = await ref.read(liveClassController.notifier).joinClass(liveClass);
      
      if (result.isSuccess) {
        final uri = Uri.parse(liveClass.zoomLink);
        if (await canLaunchUrl(uri)) {
          await launchUrl(uri, mode: LaunchMode.externalApplication);
        } else {
          ApGlobalFunctions.showCustomSnackbar(
            message: 'Could not open Zoom link',
            isSuccess: false,
          );
        }
      } else {
        ApGlobalFunctions.showCustomSnackbar(
          message: result.message,
          isSuccess: false,
        );
      }
    } catch (error) {
      ApGlobalFunctions.showCustomSnackbar(
        message: 'Failed to join class',
        isSuccess: false,
      );
    }
  }

  void _showClassDetails(BuildContext context, LiveClass liveClass) {
    showDialog(
      context: context,
      builder: (context) => ClassDetailsDialog(liveClass: liveClass),
    );
  }
}

class _SectionHeader extends StatelessWidget {
  final String title;
  final String subtitle;
  final bool isLive;

  const _SectionHeader({
    required this.title,
    required this.subtitle,
    required this.isLive,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: 16.w),
      child: Row(
        children: [
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: [
                    Text(
                      title,
                      style: TextStyle(
                        fontSize: 18.sp,
                        fontWeight: FontWeight.bold,
                        color: colors(context).titleTextColor,
                      ),
                    ),
                    if (isLive) ...[
                      8.pw,
                      Container(
                        padding: EdgeInsets.symmetric(horizontal: 8.w, vertical: 2.h),
                        decoration: BoxDecoration(
                          color: Colors.red,
                          borderRadius: BorderRadius.circular(12.r),
                        ),
                        child: Row(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Icon(
                              Icons.circle,
                              size: 8.r,
                              color: Colors.white,
                            ),
                            4.pw,
                            Text(
                              'LIVE',
                              style: TextStyle(
                                fontSize: 10.sp,
                                fontWeight: FontWeight.bold,
                                color: Colors.white,
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ],
                ),
                4.ph,
                Text(
                  subtitle,
                  style: TextStyle(
                    fontSize: 12.sp,
                    color: colors(context).bodyTextColor,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

class LiveClassCard extends StatelessWidget {
  final LiveClass liveClass;
  final VoidCallback onTap;

  const LiveClassCard({
    super.key,
    required this.liveClass,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 280.w,
        decoration: BoxDecoration(
          color: colors(context).scaffoldBackgroundColor,
          borderRadius: BorderRadius.circular(12.r),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.05),
              blurRadius: 8.r,
              offset: Offset(0, 2.h),
            ),
          ],
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Thumbnail with status badge
            Stack(
              children: [
                ClipRRect(
                  borderRadius: BorderRadius.vertical(top: Radius.circular(12.r)),
                  child: Container(
                    height: 100.h,
                    width: double.infinity,
                    color: colors(context).primaryColor?.withOpacity(0.1) ?? Colors.blue.withOpacity(0.1),
                    child: Icon(
                      Icons.video_call,
                      size: 40.r,
                      color: colors(context).primaryColor ?? Colors.blue,
                    ),
                  ),
                ),
                Positioned(
                  top: 8.h,
                  left: 8.w,
                  child: Container(
                    padding: EdgeInsets.symmetric(horizontal: 8.w, vertical: 4.h),
                    decoration: BoxDecoration(
                      color: liveClass.isLive ? Colors.red : colors(context).primaryColor,
                      borderRadius: BorderRadius.circular(8.r),
                    ),
                    child: Text(
                      liveClass.isLive ? 'LIVE' : liveClass.timeRemaining,
                      style: TextStyle(
                        fontSize: 10.sp,
                        fontWeight: FontWeight.bold,
                        color: Colors.white,
                      ),
                    ),
                  ),
                ),
                if (liveClass.isLive)
                  Positioned(
                    top: 8.h,
                    right: 8.w,
                    child: Container(
                      padding: EdgeInsets.symmetric(horizontal: 6.w, vertical: 2.h),
                      decoration: BoxDecoration(
                        color: Colors.black.withOpacity(0.6),
                        borderRadius: BorderRadius.circular(6.r),
                      ),
                      child: Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Icon(
                            Icons.people,
                            size: 12.r,
                            color: Colors.white,
                          ),
                          2.pw,
                          Text(
                            '${liveClass.participantCount}',
                            style: TextStyle(
                              fontSize: 10.sp,
                              color: Colors.white,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
              ],
            ),
            // Content
            Expanded(
              child: Padding(
                padding: EdgeInsets.all(12.w),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      liveClass.title,
                      style: TextStyle(
                        fontSize: 14.sp,
                        fontWeight: FontWeight.w600,
                        color: colors(context).titleTextColor,
                      ),
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                    4.ph,
                    Text(
                      liveClass.instructorName,
                      style: TextStyle(
                        fontSize: 12.sp,
                        color: colors(context).bodyTextColor,
                      ),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                    8.ph,
                    Row(
                      children: [
                        Icon(
                          Icons.access_time,
                          size: 12.r,
                          color: colors(context).bodyTextColor,
                        ),
                        4.pw,
                        Text(
                          liveClass.formattedTime,
                          style: TextStyle(
                            fontSize: 11.sp,
                            color: colors(context).bodyTextColor,
                          ),
                        ),
                        const Spacer(),
                        Container(
                          padding: EdgeInsets.symmetric(horizontal: 8.w, vertical: 2.h),
                          decoration: BoxDecoration(
                            color: colors(context).primaryColor?.withOpacity(0.1) ?? Colors.blue.withOpacity(0.1),
                            borderRadius: BorderRadius.circular(8.r),
                          ),
                          child: Text(
                            liveClass.category,
                            style: TextStyle(
                              fontSize: 10.sp,
                              color: colors(context).primaryColor ?? Colors.blue,
                              fontWeight: FontWeight.w500,
                            ),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class ClassDetailsDialog extends StatelessWidget {
  final LiveClass liveClass;

  const ClassDetailsDialog({super.key, required this.liveClass});

  @override
  Widget build(BuildContext context) {
    return AlertDialog(
      title: Text(liveClass.title),
      content: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text('Instructor: ${liveClass.instructorName}'),
          8.ph,
          Text('Time: ${liveClass.formattedTime}'),
          8.ph,
          Text('Meeting ID: ${liveClass.meetingId}'),
          if (liveClass.passcode.isNotEmpty) ...[
            8.ph,
            Text('Passcode: ${liveClass.passcode}'),
          ],
          8.ph,
          Text(liveClass.description),
        ],
      ),
      actions: [
        TextButton(
          onPressed: () => Navigator.of(context).pop(),
          child: const Text('Close'),
        ),
      ],
    );
  }
}