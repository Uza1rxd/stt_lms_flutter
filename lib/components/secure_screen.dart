import 'package:flutter/material.dart';
import 'package:ready_lms/service/screenshot_prevention_service.dart';
import 'package:ready_lms/utils/global_function.dart';

/// A widget that automatically enables screenshot prevention when visible
/// and disables it when disposed. Use this for sensitive screens.
class SecureScreen extends StatefulWidget {
  final Widget child;
  final bool showSecurityNotice;
  final String? securityMessage;

  const SecureScreen({
    super.key,
    required this.child,
    this.showSecurityNotice = true,
    this.securityMessage,
  });

  @override
  State<SecureScreen> createState() => _SecureScreenState();
}

class _SecureScreenState extends State<SecureScreen>
    with WidgetsBindingObserver {
  bool _isProtectionEnabled = false;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addObserver(this);
    _enableProtection();
  }

  @override
  void dispose() {
    WidgetsBinding.instance.removeObserver(this);
    _disableProtection();
    super.dispose();
  }

  @override
  void didChangeAppLifecycleState(AppLifecycleState state) {
    super.didChangeAppLifecycleState(state);
    
    switch (state) {
      case AppLifecycleState.resumed:
        _enableProtection();
        break;
      case AppLifecycleState.paused:
      case AppLifecycleState.inactive:
        // Keep protection enabled when app goes to background
        break;
      case AppLifecycleState.detached:
        _disableProtection();
        break;
      case AppLifecycleState.hidden:
        break;
    }
  }

  Future<void> _enableProtection() async {
    try {
      await ScreenshotPreventionService.enableForScreen();
      setState(() {
        _isProtectionEnabled = true;
      });

      if (widget.showSecurityNotice) {
        _showSecurityNotice();
      }
    } catch (e) {
      debugPrint('Failed to enable screenshot protection: $e');
    }
  }

  Future<void> _disableProtection() async {
    try {
      await ScreenshotPreventionService.disableForScreen();
      setState(() {
        _isProtectionEnabled = false;
      });
    } catch (e) {
      debugPrint('Failed to disable screenshot protection: $e');
    }
  }

  void _showSecurityNotice() {
    WidgetsBinding.instance.addPostFrameCallback((_) {
      ApGlobalFunctions.showCustomSnackbar(
        message: widget.securityMessage ?? 
            'Screen recording and screenshots are disabled for security',
        isSuccess: true,
      );
    });
  }

  @override
  Widget build(BuildContext context) {
    return Stack(
      children: [
        widget.child,
        
        // Security indicator (optional)
        if (_isProtectionEnabled)
          Positioned(
            top: MediaQuery.of(context).padding.top + 10,
            right: 16,
            child: Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
              decoration: BoxDecoration(
                color: Colors.red.withOpacity(0.8),
                borderRadius: BorderRadius.circular(8),
              ),
              child: Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  const Icon(
                    Icons.security,
                    color: Colors.white,
                    size: 12,
                  ),
                  const SizedBox(width: 4),
                  Text(
                    'SECURE',
                    style: const TextStyle(
                      color: Colors.white,
                      fontSize: 10,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ],
              ),
            ),
          ),
      ],
    );
  }
}

/// A mixin that provides screenshot protection methods to any StatefulWidget
mixin SecureScreenMixin<T extends StatefulWidget> on State<T> {
  bool _isSecured = false;

  Future<void> enableScreenSecurity() async {
    if (!_isSecured) {
      await ScreenshotPreventionService.enableForScreen();
      _isSecured = true;
    }
  }

  Future<void> disableScreenSecurity() async {
    if (_isSecured) {
      await ScreenshotPreventionService.disableForScreen();
      _isSecured = false;
    }
  }

  @override
  void dispose() {
    disableScreenSecurity();
    super.dispose();
  }
}