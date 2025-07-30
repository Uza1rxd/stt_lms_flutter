import 'package:dio/dio.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:ready_lms/config/app_constants.dart';
import 'package:ready_lms/service/base_service/live_class.dart';
import 'package:ready_lms/utils/api_client.dart';

class LiveClassServiceProvider extends LiveClassService {
  final Ref ref;
  LiveClassServiceProvider(this.ref);

  @override
  Future<Response> getLiveClasses() async {
    final response = await ref
        .read(apiClientProvider)
        .get('${AppConstants.baseUrl}/live-classes');
    return response;
  }

  @override
  Future<Response> joinLiveClass({required int classId}) async {
    final response = await ref
        .read(apiClientProvider)
        .post('${AppConstants.baseUrl}/live-classes/$classId/join');
    return response;
  }
}

final liveClassServiceProvider = Provider((ref) => LiveClassServiceProvider(ref));