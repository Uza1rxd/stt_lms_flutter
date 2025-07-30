import 'package:ready_lms/components/busy_loader.dart';
import 'package:ready_lms/components/shimmer.dart';
import 'package:ready_lms/controllers/downloads_tab.dart';
import 'package:ready_lms/model/hive_mode/hive_cart_model.dart';
import 'package:ready_lms/view/downloads/component/download_card.dart';
import 'package:ready_lms/generated/l10n.dart';
import 'package:ready_lms/utils/entensions.dart';
import 'package:ready_lms/utils/global_function.dart';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

class DownloadsTab extends ConsumerStatefulWidget {
  const DownloadsTab({super.key});

  @override
  ConsumerState<ConsumerStatefulWidget> createState() => _DownloadsTabState();
}

class _DownloadsTabState extends ConsumerState<DownloadsTab> {
  ScrollController scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((timeStamp) {
      init(isRefresh: true);
    });
  }

  Future<void> init({bool isRefresh = false}) async {
    await ref
        .read(downloadsTabController.notifier)
        .getDownloadsList(isRefresh: isRefresh)
        .then(
      (value) {
        if (value.isSuccess) {
          setState(() {});
        }
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    bool isLoading = ref.watch(downloadsTabController).isLoading;
    List<HiveCartModel> downloadsList =
        ref.watch(downloadsTabController).downloadsList;
    
    return Scaffold(
      appBar: ApGlobalFunctions.cAppBar(header: Text(S.of(context).downloads)),
      body: RefreshIndicator(
        onRefresh: () async {
          init(isRefresh: true);
        },
        child: SafeArea(
          child: isLoading
              ? const ShimmerWidget()
              : !isLoading && downloadsList.isEmpty
                  ? ApGlobalFunctions.noItemFound(context: context)
                  : SingleChildScrollView(
                      controller: scrollController,
                      physics: const AlwaysScrollableScrollPhysics(),
                      child: Column(
                        children: [
                          ListView.builder(
                            shrinkWrap: true,
                            physics: const NeverScrollableScrollPhysics(),
                            itemCount: downloadsList.length,
                            itemBuilder: (context, index) {
                              return Padding(
                                padding: EdgeInsets.symmetric(
                                  horizontal: 20.w,
                                  vertical: 8.h,
                                ),
                                child: DownloadCard(
                                  model: downloadsList[index],
                                  onDelete: () async {
                                    // Remove the download
                                    await ref
                                        .read(downloadsTabController.notifier)
                                        .removeDownload(
                                            id: downloadsList[index].id!);
                                    // Refresh the list
                                    init(isRefresh: true);
                                  },
                                ),
                              );
                            },
                          ),
                          if (isLoading) const BusyLoader(),
                          50.ph,
                        ],
                      ),
                    ),
        ),
      ),
    );
  }
}