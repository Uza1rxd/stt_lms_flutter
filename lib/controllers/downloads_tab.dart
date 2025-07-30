import 'dart:typed_data';
import 'package:flutter/foundation.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:ready_lms/model/common/common_response_model.dart';
import 'package:ready_lms/model/hive_mode/hive_cart_model.dart';
import 'package:ready_lms/config/hive_contants.dart';
import 'package:hive_flutter/hive_flutter.dart';

class DownloadsTabController extends StateNotifier<Downloads> {
  final Ref ref;
  late Box<HiveCartModel> _cartBox;

  DownloadsTabController(super.state, this.ref) {
    _cartBox = Hive.box<HiveCartModel>(AppHSC.cartBox);
  }

  Future<CommonResponse> getDownloadsList({bool isRefresh = false}) async {
    if (isRefresh) {
      state = state.copyWith(isLoading: true);
    }
    
    bool isSuccess = false;
    
    try {
      // Get all downloaded content from Hive
      final cartItems = _cartBox.values.toList();
      
      if (isRefresh) {
        state.downloadsList.clear();
      }
      
      // Add all downloaded items to the list
      state.downloadsList.addAll(cartItems);
      
      // Add dummy data for demonstration
      if (cartItems.isEmpty) {
        _addDummyData();
      }
      
      isSuccess = true;
      
      return CommonResponse(
        isSuccess: isSuccess,
        message: 'Downloads loaded successfully',
        response: state.downloadsList.isNotEmpty,
      );
    } catch (error) {
      debugPrint('Error loading downloads: $error');
      return CommonResponse(
        isSuccess: isSuccess,
        message: error.toString(),
      );
    } finally {
      state = state.copyWith(isLoading: false);
    }
  }

  void _addDummyData() {
    final dummyDownloads = [
      HiveCartModel(
        id: 1001,
        fileName: 'Flutter Development Complete Guide.pdf',
        fileExtension: 'pdf',
        uniqueNumber: '1701234567',
        data: _generateDummyBytes(2500000), // 2.5 MB
      ),
      HiveCartModel(
        id: 1002,
        fileName: 'Advanced Dart Programming.pdf',
        fileExtension: 'pdf',
        uniqueNumber: '1701234568',
        data: _generateDummyBytes(1800000), // 1.8 MB
      ),
      HiveCartModel(
        id: 1003,
        fileName: 'Mobile App Design Principles.mp4',
        fileExtension: 'mp4',
        uniqueNumber: '1701234569',
        data: _generateDummyBytes(15000000), // 15 MB
      ),
      HiveCartModel(
        id: 1004,
        fileName: 'JavaScript Fundamentals Audio Lecture.mp3',
        fileExtension: 'mp3',
        uniqueNumber: '1701234570',
        data: _generateDummyBytes(8500000), // 8.5 MB
      ),
      HiveCartModel(
        id: 1005,
        fileName: 'React Native Tutorial Video.mp4',
        fileExtension: 'mp4',
        uniqueNumber: '1701234571',
        data: _generateDummyBytes(22000000), // 22 MB
      ),
      HiveCartModel(
        id: 1006,
        fileName: 'Database Design Cheat Sheet.jpg',
        fileExtension: 'jpg',
        uniqueNumber: '1701234572',
        data: _generateDummyBytes(450000), // 450 KB
      ),
      HiveCartModel(
        id: 1007,
        fileName: 'API Development Best Practices.pdf',
        fileExtension: 'pdf',
        uniqueNumber: '1701234573',
        data: _generateDummyBytes(3200000), // 3.2 MB
      ),
      HiveCartModel(
        id: 1008,
        fileName: 'UI UX Design Workshop Recording.mp4',
        fileExtension: 'mp4',
        uniqueNumber: '1701234574',
        data: _generateDummyBytes(45000000), // 45 MB
      ),
    ];

    state.downloadsList.addAll(dummyDownloads);
    state = state._update(state);
  }

  Uint8List _generateDummyBytes(int size) {
    // Generate dummy bytes to simulate file size
    return Uint8List.fromList(List.generate(size, (index) => 0));
  }

  Future<CommonResponse> removeDownload({required int id}) async {
    bool isSuccess = false;
    try {
      // Check if it's a real Hive item first
      final cartItems = _cartBox.values.toList();
      final itemIndex = cartItems.indexWhere((element) => element.id == id);
      
      if (itemIndex != -1) {
        // Remove from Hive box
        await _cartBox.deleteAt(itemIndex);
        isSuccess = true;
      }
      
      // Remove from the state list (works for both real and dummy data)
      final stateItemExists = state.downloadsList.any((element) => element.id == id);
      if (stateItemExists) {
        state.downloadsList.removeWhere((element) => element.id == id);
        state = state._update(state);
        isSuccess = true;
      }
      
      return CommonResponse(
        isSuccess: isSuccess,
        message: isSuccess ? 'Download removed successfully' : 'Download not found',
      );
    } catch (error) {
      debugPrint('Error removing download: $error');
      return CommonResponse(
        isSuccess: isSuccess,
        message: error.toString(),
      );
    }
  }

  Future<bool> isContentDownloaded({required int id}) async {
    final cartItems = _cartBox.values.toList();
    return cartItems.any((element) => element.id == id);
  }

  Future<HiveCartModel?> getDownloadedContent({required int id}) async {
    final cartItems = _cartBox.values.toList();
    try {
      return cartItems.firstWhere((element) => element.id == id);
    } catch (e) {
      return null;
    }
  }

  void clearDownloadsList() {
    state.downloadsList.clear();
    state = state._update(state);
  }

  void clearDummyData() {
    // Remove only dummy data (IDs 1001-1008)
    state.downloadsList.removeWhere((element) => 
        element.id != null && element.id! >= 1001 && element.id! <= 1008);
    state = state._update(state);
  }
}

class Downloads {
  List<HiveCartModel> downloadsList;
  bool isLoading;

  Downloads({
    required this.downloadsList,
    this.isLoading = false,
  });

  Downloads copyWith({
    List<HiveCartModel>? downloadsList,
    bool? isLoading,
  }) {
    return Downloads(
      isLoading: isLoading ?? this.isLoading,
      downloadsList: downloadsList ?? this.downloadsList,
    );
  }

  Downloads _update(Downloads state) {
    return Downloads(
      isLoading: state.isLoading,
      downloadsList: state.downloadsList,
    );
  }
}

final downloadsTabController =
    StateNotifierProvider<DownloadsTabController, Downloads>(
  (ref) => DownloadsTabController(Downloads(downloadsList: []), ref),
);