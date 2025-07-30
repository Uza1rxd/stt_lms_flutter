import 'dart:io';
import 'package:flutter/foundation.dart';
import 'package:flutter/services.dart';
import 'package:screen_protector/screen_protector.dart';

class ScreenshotPreventionService {
  static const bool _enableInDebug = true; // Enable for testing and production
  
  /// Initialize screenshot prevention
  static Future<void> initialize() async {
    try {
      // Only enable in release mode (unless debug is explicitly enabled)
      if (kDebugMode && !_enableInDebug) {
        debugPrint('Screenshot prevention disabled in debug mode');
        return;
      }

      if (Platform.isAndroid) {
        await _enableAndroidProtection();
      } else if (Platform.isIOS) {
        await _enableiOSProtection();
      }
      
      debugPrint('Screenshot prevention enabled successfully');
    } catch (e) {
      debugPrint('Failed to enable screenshot prevention: $e');
    }
  }

  /// Disable screenshot prevention
  static Future<void> disable() async {
    try {
      if (Platform.isAndroid) {
        await _disableAndroidProtection();
      } else if (Platform.isIOS) {
        await _disableiOSProtection();
      }
      
      debugPrint('Screenshot prevention disabled successfully');
    } catch (e) {
      debugPrint('Failed to disable screenshot prevention: $e');
    }
  }

  /// Enable protection for Android
  static Future<void> _enableAndroidProtection() async {
    await ScreenProtector.protectDataLeakageOn();
  }

  /// Disable protection for Android
  static Future<void> _disableAndroidProtection() async {
    await ScreenProtector.protectDataLeakageOff();
  }

  /// Enable protection for iOS
  static Future<void> _enableiOSProtection() async {
    await ScreenProtector.protectDataLeakageOn();
    // Additional iOS-specific protection can be added here
  }

  /// Disable protection for iOS
  static Future<void> _disableiOSProtection() async {
    await ScreenProtector.protectDataLeakageOff();
  }

  /// Enable protection for specific screen (call this in sensitive screens)
  static Future<void> enableForScreen() async {
    try {
      if (kDebugMode && !_enableInDebug) return;
      
      await ScreenProtector.protectDataLeakageOn();
    } catch (e) {
      debugPrint('Failed to enable screen protection: $e');
    }
  }

  /// Disable protection for specific screen
  static Future<void> disableForScreen() async {
    try {
      await ScreenProtector.protectDataLeakageOff();
    } catch (e) {
      debugPrint('Failed to disable screen protection: $e');
    }
  }

  /// Check if screenshot prevention is supported
  static Future<bool> isSupported() async {
    try {
      if (Platform.isAndroid) {
        // Check Android version (FLAG_SECURE works on most Android versions)
        return true;
      } else if (Platform.isIOS) {
        // iOS protection is supported on iOS 13+
        return true;
      }
      return false;
    } catch (e) {
      return false;
    }
  }

  /// Show alert when screenshot is attempted (iOS specific)
  static void showScreenshotAttemptAlert() {
    debugPrint('Screenshot attempt detected!');
    // You can show a custom alert or snackbar here
  }
}

/// Mixin to add screenshot prevention to specific screens
mixin ScreenshotProtectionMixin {
  
  /// Enable protection when screen is shown
  Future<void> enableScreenshotProtection() async {
    await ScreenshotPreventionService.enableForScreen();
  }

  /// Disable protection when screen is hidden
  Future<void> disableScreenshotProtection() async {
    await ScreenshotPreventionService.disableForScreen();
  }
}