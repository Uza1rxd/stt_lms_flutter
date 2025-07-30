import 'dart:async';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:ready_lms/components/shimmer.dart';
import 'package:ready_lms/controllers/category.dart';
import 'package:ready_lms/controllers/courses/course.dart';
import 'package:ready_lms/generated/l10n.dart';
import 'package:ready_lms/model/category.dart';
import 'package:ready_lms/routes.dart';
import 'package:ready_lms/service/hive_service.dart';
import 'package:ready_lms/utils/context_less_nav.dart';
import 'package:ready_lms/utils/entensions.dart';
import 'package:ready_lms/utils/global_function.dart';
import 'package:ready_lms/view/home_tab/widget/all_course.dart';
import 'package:ready_lms/view/home_tab/widget/category.dart';
import 'package:ready_lms/view/home_tab/widget/popular_course.dart';
import 'package:ready_lms/view/home_tab/widget/viewall_card.dart';
import 'package:ready_lms/view/home_tab/widget/welcome_card.dart';
import 'package:ready_lms/view/home_tab/widget/live_classes.dart';
import 'package:ready_lms/controllers/live_class.dart';

class HomeTab extends ConsumerStatefulWidget {
  const HomeTab({super.key});

  @override
  ConsumerState<ConsumerStatefulWidget> createState() => _HomeState();
}

class _HomeState extends ConsumerState<HomeTab> {
  final courseController = StateNotifierProvider<CourseController, Course>(
      (ref) => CourseController(Course(courseList: [], mostPopular: []), ref));
  List<CategoryModel> categoryList = [];
  bool _hasInitialized = false;
  bool _initializationFailed = false;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((timeStamp) {
      _initWithTimeout();
    });
  }

  _initWithTimeout() async {
    // Add timeout to prevent infinite loading
    try {
      await Future.any<void>([
        init(),
        Future.delayed(const Duration(seconds: 15)).then<void>((_) {
          throw TimeoutException('Initialization timeout', const Duration(seconds: 15));
        }),
      ]);
    } catch (error) {
      debugPrint('Home tab initialization failed: $error');
      setState(() {
        _initializationFailed = true;
        _hasInitialized = true;
      });
    }
  }

  init() async {
    try {
      // Fetch categories with error handling
      final categoryResponse = await ref
          .read(categoryController.notifier)
          .getCategories(query: {'is_featured': true});
      
      if (categoryResponse.isSuccess) {
        setState(() {
          categoryList.addAll(categoryResponse.response);
        });
      }
      
      // Fetch courses
      await ref.read(courseController.notifier).getHomeTabInit();
      
      // Fetch live classes (with mock data for now)
      ref.read(liveClassController.notifier).loadMockData();
      
      setState(() {
        _hasInitialized = true;
      });
    } catch (error) {
      debugPrint('Home tab init error: $error');
      setState(() {
        _initializationFailed = true;
        _hasInitialized = true;
      });
    }
  }

  Widget _buildErrorWidget() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(
            Icons.error_outline,
            size: 64,
            color: Colors.grey[400],
          ),
          16.ph,
          Text(
            'Something went wrong',
            style: TextStyle(
              fontSize: 16.sp,
              color: Colors.grey[600],
            ),
          ),
          16.ph,
          ElevatedButton(
            onPressed: () {
              setState(() {
                _hasInitialized = false;
                _initializationFailed = false;
                categoryList.clear();
              });
              ref.read(courseController.notifier).removeListData();
              ref.read(liveClassController.notifier).loadMockData();
              _initWithTimeout();
            },
            child: const Text('Retry'),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: ApGlobalFunctions.cAppBar(
          header: Image.asset(
        'assets/images/stt_logo.png',
        height: 32.h,
        fit: BoxFit.contain,
        errorBuilder: (context, error, stackTrace) {
          return Container(
            height: 32.h,
            width: 60.w,
            decoration: BoxDecoration(
              color: Colors.grey[200],
              borderRadius: BorderRadius.circular(4),
            ),
            child: const Icon(
              Icons.image_not_supported,
              color: Colors.grey,
              size: 16,
            ),
          );
        },
      )),
      body: RefreshIndicator(
        onRefresh: () async {
          setState(() {
            _hasInitialized = false;
            _initializationFailed = false;
            categoryList.clear();
          });
          ref.read(courseController.notifier).removeListData();
          ref.read(liveClassController.notifier).loadMockData();
          await _initWithTimeout();
        },
        child: SafeArea(
            child: !_hasInitialized
                ? const ShimmerWidget()
                : _initializationFailed
                    ? _buildErrorWidget()
                    : SingleChildScrollView(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Container(
                          color: context.color.surface,
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              10.ph,
                              WelcomeCard(
                                totalCourse:
                                    ref.watch(courseController).totalCourse,
                              ),
                              20.ph,
                            ],
                          ),
                        ),
                        // Live Classes Section
                        const LiveClassesSection(),
                        Container(
                          color: context.color.surface,
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              ViewAllCard(
                                title: S.of(context).categories,
                                onTap: () {
                                  context.nav
                                      .pushNamed(Routes.allCategoryScreen);
                                },
                              ),
                              14.ph,
                              Category(
                                categoryList: categoryList,
                              ),
                              20.ph,
                            ],
                          ),
                        ),
                        20.ph,
                        ViewAllCard(
                          title: S.of(context).mostPopularCourse,
                          onTap: () {
                            context.nav.pushNamed(Routes.allCourseScreen,
                                arguments: {'popular': true});
                          },
                        ),
                        16.ph,
                        PopularCourses(
                          courseList: ref.watch(courseController).mostPopular,
                        ),
                        20.ph,
                        ViewAllCard(
                          title: S.of(context).allCourse,
                          showViewAll: false,
                        ),
                        AllCourses(
                          courseList: ref.watch(courseController).courseList,
                        )
                      ],
                    ),
                  )),
      ),
    );
  }
}
