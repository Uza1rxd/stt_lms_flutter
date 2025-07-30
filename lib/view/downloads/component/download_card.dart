import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:ready_lms/components/buttons/app_button.dart';
import 'package:ready_lms/config/app_components.dart';
import 'package:ready_lms/config/app_text_style.dart';
import 'package:ready_lms/config/theme.dart';
import 'package:ready_lms/model/hive_mode/hive_cart_model.dart';
import 'package:ready_lms/routes.dart';
import 'package:ready_lms/utils/context_less_nav.dart';
import 'package:ready_lms/utils/entensions.dart';

class DownloadCard extends StatelessWidget {
  const DownloadCard({
    super.key,
    required this.model,
    required this.onDelete,
  });

  final HiveCartModel model;
  final VoidCallback onDelete;

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      decoration: BoxDecoration(
        borderRadius: AppComponents.defaultBorderRadiusSmall,
        color: context.color.onSecondary,
      ),
      padding: EdgeInsets.all(16.h),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              // File type icon
              Container(
                width: 40.h,
                height: 40.h,
                decoration: BoxDecoration(
                  color: (colors(context).primaryColor ?? Colors.blue).withOpacity(0.1),
                  borderRadius: BorderRadius.circular(8.r),
                ),
                child: Center(
                  child: SvgPicture.asset(
                    _getFileIcon(model.fileExtension ?? ''),
                    width: 24.h,
                    height: 24.h,
                    color: colors(context).primaryColor,
                  ),
                ),
              ),
              12.pw,
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      model.fileName ?? 'Unknown File',
                      style: AppTextStyle(context).bodyText.copyWith(
                        fontWeight: FontWeight.w600,
                      ),
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                    4.ph,
                    Text(
                      '${model.fileExtension?.toUpperCase() ?? 'FILE'} • ${_getFileSize(model.data?.length ?? 0)}',
                      style: AppTextStyle(context).bodyTextSmall.copyWith(
                        color: colors(context).hintTextColor,
                        fontSize: 12.sp,
                      ),
                    ),
                  ],
                ),
              ),
              // Delete button
              GestureDetector(
                onTap: () => _showDeleteConfirmation(context),
                child: Container(
                  padding: EdgeInsets.all(8.h),
                  decoration: BoxDecoration(
                    color: Colors.red.withOpacity(0.1),
                    borderRadius: BorderRadius.circular(6.r),
                  ),
                  child: Icon(
                    Icons.delete_outline,
                    color: Colors.red,
                    size: 20.h,
                  ),
                ),
              ),
            ],
          ),
          16.ph,
          Row(
            children: [
              Expanded(
                child: AppButton(
                  title: 'Open File',
                  titleColor: context.color.surface,
                  onTap: () {
                    // Check if this is dummy data (IDs 1001-1008)
                    if (model.id != null && model.id! >= 1001 && model.id! <= 1008) {
                      // Show message for dummy data
                      ScaffoldMessenger.of(context).showSnackBar(
                        SnackBar(
                          content: Text('This is demo content. In a real app, "${model.fileName}" would open here.'),
                          backgroundColor: colors(context).primaryColor ?? Colors.blue,
                          duration: const Duration(seconds: 3),
                        ),
                      );
                    } else {
                      // Open the actual downloaded file
                      context.nav.pushNamed(
                        Routes.pdfScreen,
                        arguments: {
                          'id': model.id,
                          'title': model.fileName,
                        },
                      );
                    }
                  },
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  String _getFileIcon(String fileExtension) {
    switch (fileExtension.toLowerCase()) {
      case 'pdf':
        return 'assets/svg/ic_note_file.svg';
      case 'mp3':
      case 'wav':
      case 'm4a':
        return 'assets/svg/ic_audio_file.svg';
      case 'mp4':
      case 'avi':
      case 'mov':
        return 'assets/svg/ic_video_file.svg';
      case 'jpg':
      case 'jpeg':
      case 'png':
      case 'gif':
        return 'assets/svg/ic_image_file.svg';
      default:
        return 'assets/svg/ic_note_file.svg';
    }
  }

  String _getFileSize(int bytes) {
    if (bytes < 1024) return '$bytes B';
    if (bytes < 1024 * 1024) return '${(bytes / 1024).toStringAsFixed(1)} KB';
    if (bytes < 1024 * 1024 * 1024) {
      return '${(bytes / (1024 * 1024)).toStringAsFixed(1)} MB';
    }
    return '${(bytes / (1024 * 1024 * 1024)).toStringAsFixed(1)} GB';
  }

  void _showDeleteConfirmation(BuildContext context) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text(
            'Delete Download',
            style: AppTextStyle(context).bodyText.copyWith(
              fontWeight: FontWeight.w600,
            ),
          ),
          content: Text(
            'Are you sure you want to delete "${model.fileName}"? This action cannot be undone.',
            style: AppTextStyle(context).bodyTextSmall,
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.of(context).pop(),
              child: Text(
                'Cancel',
                style: AppTextStyle(context).bodyTextSmall.copyWith(
                  color: colors(context).hintTextColor,
                ),
              ),
            ),
            TextButton(
              onPressed: () {
                Navigator.of(context).pop();
                onDelete();
              },
              child: Text(
                'Delete',
                style: AppTextStyle(context).bodyTextSmall.copyWith(
                  color: Colors.red,
                  fontWeight: FontWeight.w600,
                ),
              ),
            ),
          ],
        );
      },
    );
  }
}