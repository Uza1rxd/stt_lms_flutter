import 'package:dio/dio.dart';

abstract class LiveClassService {
  Future<Response> getLiveClasses();
  Future<Response> joinLiveClass({required int classId});
}