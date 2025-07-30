import 'package:connectivity_wrapper/connectivity_wrapper.dart';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hive_flutter/hive_flutter.dart';
import 'package:ready_lms/components/offline.dart';
import 'package:ready_lms/config/hive_contants.dart';
import 'package:ready_lms/controllers/others.dart';
import 'package:ready_lms/routes.dart';
import 'package:ready_lms/service/hive_service.dart';
import 'package:ready_lms/utils/api_client.dart';
import 'package:ready_lms/utils/context_less_nav.dart';

class SplashScreen extends ConsumerStatefulWidget {
  const SplashScreen({super.key});

  @override
  ConsumerState<SplashScreen> createState() => _SplashScreenState();
}

class _SplashScreenState extends ConsumerState<SplashScreen>
    with SingleTickerProviderStateMixin {
  bool _hasNavigated = false;

  @override
  void initState() {
    super.initState();

    WidgetsBinding.instance.addPostFrameCallback((timeStamp) {
      _initializeApp();
    });
  }

  void _initializeApp() async {
    ConnectivityWrapper.instance.onStatusChange.listen((event) {
      if (event == ConnectivityStatus.CONNECTED && !_hasNavigated) {
        _fetchMasterDataWithFallback();
      }
    });

    // If already connected, start immediately
    try {
      final isConnected = await ConnectivityWrapper.instance.isConnected;
      if (isConnected && !_hasNavigated) {
        _fetchMasterDataWithFallback();
      }
    } catch (e) {
      debugPrint('Connectivity check failed: $e');
      // Proceed anyway after a brief delay
      Future.delayed(const Duration(seconds: 2), () {
        if (!_hasNavigated) {
          _fetchMasterDataWithFallback();
        }
      });
    }

    // Fallback timer - navigate after 10 seconds regardless of API response
    Future.delayed(const Duration(seconds: 10), () {
      if (!_hasNavigated) {
        _navigateToNextScreen();
      }
    });
  }

  void _fetchMasterDataWithFallback() async {
    try {
      final response = await ref.read(othersController.notifier).getMasterData();
      
      // Wait for the controller to finish (not busy)
      while (ref.read(othersController)) {
        await Future.delayed(const Duration(milliseconds: 100));
      }

      // Navigate whether the API call succeeded or failed
      if (!_hasNavigated) {
        _navigateToNextScreen();
      }
    } catch (error) {
      debugPrint('Master data fetch failed: $error');
      if (!_hasNavigated) {
        _navigateToNextScreen();
      }
    }
  }

  void _navigateToNextScreen() {
    if (_hasNavigated) return;
    _hasNavigated = true;

    final appSettingsBox = Hive.box(AppHSC.appSettingsBox);
    var firstOpen = appSettingsBox.get(AppHSC.firstOpen, defaultValue: true);

    // Update token if user is not a guest
    if (!ref.read(hiveStorageProvider).isGuest()) {
      final token = ref.read(hiveStorageProvider).getAuthToken();
      if (token != null) {
        ref.read(apiClientProvider).updateToken(token: token);
      }
    }

    context.nav.pushNamedAndRemoveUntil(
        firstOpen ? Routes.authHomeScreen : Routes.dashboard,
        (route) => false);
  }

  @override
  Widget build(BuildContext context) {
    return ConnectivityWidgetWrapper(
      offlineWidget: const OfflineScreen(),
      child: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Image.asset(
              'assets/images/stt_logo.png',
              width: 150.h,
              height: 150.h,
              fit: BoxFit.contain,
              errorBuilder: (context, error, stackTrace) {
                return Container(
                  width: 150.h,
                  height: 150.h,
                  decoration: BoxDecoration(
                    color: Colors.grey[200],
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: const Icon(
                    Icons.image_not_supported,
                    color: Colors.grey,
                    size: 40,
                  ),
                );
              },
            )
          ],
        ),
      ),
    );
  }
}
