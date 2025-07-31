import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:ready_lms/components/buttons/app_button.dart';
import 'package:ready_lms/components/custom_dot.dart';

import 'package:ready_lms/components/shimmer.dart';
import 'package:ready_lms/config/app_components.dart';
import 'package:ready_lms/config/app_text_style.dart';
import 'package:ready_lms/config/theme.dart';
import 'package:ready_lms/controllers/my_course_tab.dart';
import 'package:ready_lms/controllers/dashboard_nav.dart';
import 'package:ready_lms/generated/l10n.dart';
import 'package:ready_lms/model/course_list.dart';
import 'package:ready_lms/routes.dart';
import 'package:ready_lms/service/hive_service.dart';
import 'package:ready_lms/utils/context_less_nav.dart';
import 'package:ready_lms/utils/entensions.dart';
import 'package:ready_lms/utils/global_function.dart';

class MyCourseTabScreen extends ConsumerStatefulWidget {
  const MyCourseTabScreen({super.key});

  @override
  ConsumerState<ConsumerStatefulWidget> createState() => _MyCourseViewState();
}

class _MyCourseViewState extends ConsumerState<MyCourseTabScreen> {
  ScrollController scrollController = ScrollController();
  bool hasMoreData = true;
  int currentPage = 1;
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((timeStamp) {
      if (!ref.read(hiveStorageProvider).isGuest()) init(isRefresh: true);
    });
    scrollController.addListener(() {
      if (scrollController.position.maxScrollExtent ==
          scrollController.position.pixels) {
        if (hasMoreData) init();
      }
    });
  }

  Future<void> init({bool isRefresh = false}) async {
    await ref
        .read(myCourseTabController.notifier)
        .getMyEnrollCourse(isRefresh: isRefresh, currentPage: currentPage)
        .then((value) {
      if (value.isSuccess) {
        if (value.response) {
          currentPage++;
        }
        hasMoreData = value.response;
        if (!hasMoreData) {
          setState(() {});
        }
      }
    });
  }

  Widget loginWidget() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.center,
      children: [
        30.ph,
        Padding(
          padding: EdgeInsets.all(20.w),
          child: Text(S.of(context).loginForGetCourse,
              textAlign: TextAlign.center,
              style: AppTextStyle(context).bodyTextSmall),
        ),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: 20.w),
          child: AppButton(
            title: S.of(context).pleaseLogin,
            titleColor: context.color.surface,
            onTap: () {
              context.nav.pushNamed(Routes.authHomeScreen);
            },
          ),
        )
      ],
    );
  }

  @override
  Widget build(BuildContext context) {
    bool isLoading = ref.watch(myCourseTabController).isLoading;
    List<CourseListModel> enrollCourseList =
        ref.watch(myCourseTabController).enrollCourseList;
    return Scaffold(
        appBar:
            ApGlobalFunctions.cAppBar(header: Text(S.of(context).myCourses)),
        body: ref.read(hiveStorageProvider).isGuest()
            ? loginWidget()
            : RefreshIndicator(
                onRefresh: () async {
                  hasMoreData = true;
                  currentPage = 1;
                  init(isRefresh: true);
                },
                child: isLoading
                    ? const ShimmerWidget()
                    : !isLoading && enrollCourseList.isEmpty
                        ? ApGlobalFunctions.noItemFound(context: context)
                        : SingleChildScrollView(
                            controller: scrollController,
                            physics: const AlwaysScrollableScrollPhysics(),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                16.ph,
                                ...List.generate(
                                    enrollCourseList.length,
                                    (index) => MyCourseCard(
                                          model: enrollCourseList[index],
                                          canReview:
                                              enrollCourseList[index].canReview,
                                          onTap: () {
                                            context.nav.pushNamed(
                                                Routes.myCourseDetails,
                                                arguments:
                                                    enrollCourseList[index].id);
                                          },
                                        ))
                              ],
                            ),
                          ),
              ));
  }
}

class MyCourseCard extends StatefulWidget {
  const MyCourseCard(
      {super.key,
      required this.onTap,
      required this.canReview,
      required this.model});
  final VoidCallback onTap;
  final CourseListModel model;
  final bool canReview;

  @override
  State<MyCourseCard> createState() => _MyCourseCardState();
}

class _MyCourseCardState extends State<MyCourseCard> {
  @override
  void initState() {
    // TODO: implement initState
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: widget.onTap,
      child: Container(
        width: double.infinity,
        decoration: BoxDecoration(
            borderRadius: AppComponents.defaultBorderRadiusSmall,
            color: context.color.surface),
        padding: EdgeInsets.all(
          16.h,
        ),
        margin: EdgeInsets.only(bottom: 12.h, left: 16.h, right: 16.h),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                const Spacer(),
                if (widget.model.submittedReview != null)
                  Row(
                    children: [
                      Text(
                        S.of(context).rated,
                        style: AppTextStyle(context).bodyTextSmall.copyWith(
                              color: context.color.inverseSurface,
                              fontSize: 10.sp,
                            ),
                      ),
                      4.pw,
                      SvgPicture.asset(
                        'assets/svg/ic_star.svg',
                        height: 14.h,
                        width: 14.h,
                      ),
                      4.pw,
                      Text(
                        "${widget.model.submittedReview?.rating.toInt() ?? '0'}/5",
                        style: AppTextStyle(context).bodyTextSmall.copyWith(
                            color: context.color.inverseSurface,
                            fontSize: 10.sp,
                            fontWeight: FontWeight.w600),
                      ),
                    ],
                  )
              ],
            ),
            8.ph,
            Text(
              widget.model.title,
              style: AppTextStyle(context).bodyTextSmall.copyWith(),
            ),
            8.ph,
            Row(
              children: [
                Container(
                  width: 16.h,
                  height: 16.h,
                  decoration: const BoxDecoration(
                    shape: BoxShape.circle,
                  ),
                  child: ClipRRect(
                    borderRadius: BorderRadius.circular(8.h),
                    child: FadeInImage.assetNetwork(
                      placeholder: 'assets/images/spinner.gif',
                      image: widget.model.instructor.profilePicture,
                      fit: BoxFit.cover,
                    ),
                  ),
                ),
                6.pw,
                Container(
                  constraints: BoxConstraints(maxWidth: 120.h),
                  child: Text(
                    widget.model.instructor.name,
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                    style: AppTextStyle(context).bodyTextSmall.copyWith(
                        fontSize: 12.sp, color: context.color.inverseSurface),
                  ),
                ),
                12.pw,
                const CustomDot(),
                12.pw,
                Text(
                  '${ApGlobalFunctions.convertMinutesToHours(widget.model.totalDuration, context)} ',
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                  style: AppTextStyle(context).bodyTextSmall.copyWith(
                      fontSize: 12.sp, color: context.color.inverseSurface),
                ),
                const Spacer(),
                SvgPicture.asset(
                  'assets/svg/ic_right_arrow.svg',
                  color: colors(context).hintTextColor,
                  width: 16.h,
                  height: 16.h,
                )
              ],
            ),
            // Download Course Button
            Consumer(builder: (context, ref, _) {
              return Padding(
                padding: EdgeInsets.only(top: 8.0.h),
                child: Container(
                  decoration: BoxDecoration(
                    color: colors(context).primaryColor?.withOpacity(0.1),
                    borderRadius: BorderRadius.circular(8.r),
                    border: Border.all(
                      color: colors(context).primaryColor ?? Colors.blue,
                      width: 1,
                    ),
                  ),
                  padding: EdgeInsets.all(12.h),
                  child: Row(
                    children: [
                      Icon(
                        Icons.download_for_offline,
                        color: colors(context).primaryColor,
                        size: 20.h,
                      ),
                      8.pw,
                      Expanded(
                        child: Text(
                          S.of(context).downloadForOffline,
                          style: AppTextStyle(context).bodyTextSmall.copyWith(
                            fontWeight: FontWeight.w500,
                            color: colors(context).primaryColor,
                          ),
                        ),
                      ),
                      GestureDetector(
                        onTap: () {
                          _showDownloadDialog(context, ref);
                        },
                        child: Container(
                          padding: EdgeInsets.symmetric(
                            horizontal: 12.w,
                            vertical: 6.h,
                          ),
                          decoration: BoxDecoration(
                            color: colors(context).primaryColor,
                            borderRadius: BorderRadius.circular(16.r),
                          ),
                          child: Text(
                            S.of(context).download,
                            style: AppTextStyle(context).bodyTextSmall.copyWith(
                              fontWeight: FontWeight.w500,
                              fontSize: 11.sp,
                              color: Colors.white,
                            ),
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              );
            })
          ],
        ),
      ),
    );
  }

  void _showDownloadDialog(BuildContext context, WidgetRef ref) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text(
            S.of(context).downloadCourse,
            style: AppTextStyle(context).subTitle,
          ),
          content: Text(
            S.of(context).downloadCourseMessage,
            style: AppTextStyle(context).bodyText,
          ),
          actions: [
            TextButton(
              onPressed: () {
                Navigator.of(context).pop();
              },
              child: Text(
                S.of(context).cancel,
                style: AppTextStyle(context).bodyText.copyWith(
                  color: colors(context).hintTextColor,
                ),
              ),
            ),
            AppButton(
              title: S.of(context).download,
              onTap: () {
                Navigator.of(context).pop();
                _startCourseDownload(context, ref);
              },
              width: 100.w,
              height: 40.h,
            ),
          ],
        );
      },
    );
  }

  void _startCourseDownload(BuildContext context, WidgetRef ref) {
    // Show success message
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(
          '${S.of(context).downloadStarted}: ${widget.model.title}',
        ),
        backgroundColor: colors(context).primaryColor,
        duration: const Duration(seconds: 3),
      ),
    );

    // Navigate to downloads tab to show the downloaded content
    ref.read(homeTabControllerProvider.notifier).state = 2; // Downloads tab index
  }
}
