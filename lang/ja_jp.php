<?php

require_once('Language.php');
require_once('en_gb.php');

class ja_jp extends en_gb
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return array
     */
    protected function _LoadDates()
    {
        $dates = [];

        $dates['general_date'] = 'Y-m-d';
        $dates['general_datetime'] = 'Y-m-d H:i:s';
        $dates['short_datetime'] = 'Y-m-d G:i';
        $dates['schedule_daily'] = 'Y-m-d (l)';
        $dates['reservation_email'] = 'Y-m-d @ G:i (e)';
        $dates['res_popup'] = 'Y-m-d G:i';
        $dates['res_popup_time'] = 'G:i';
        $dates['short_reservation_date'] = 'Y-m-d G:i';
        $dates['dashboard'] = 'Y-m-d (l) G:i';
        $dates['period_time'] = 'G:i';
        $dates['mobile_reservation_date'] = 'M/d G:i';
        $dates['general_date_js'] = "yy-mm-dd";
        $dates['general_time_js'] = 'h:mm tt';
        $dates['momentjs_datetime'] = 'YY-m-d h:mm A';
        $dates['calendar_time'] = 'h:mmt';
        $dates['calendar_dates'] = 'm/d';
        $dates['embedded_date'] = 'D d';
        $dates['embedded_time'] = 'G:i';
        $dates['embedded_datetime'] = 'm/d G:i';
        $dates['report_date'] = '%m/%d';

        $this->Dates = $dates;

        return $this->Dates;
    }

    /**
     * @return array
     */
    protected function _LoadStrings()
    {
        $strings = [];

        $strings['FirstName'] = '名';
        $strings['LastName'] = '姓';
        $strings['Timezone'] = 'タイムゾーン';
        $strings['Edit'] = '編集';
        $strings['Change'] = '変更';
        $strings['Rename'] = '名称変更';
        $strings['Remove'] = '削除';
        $strings['Delete'] = '削除';
        $strings['Update'] = '更新';
        $strings['Cancel'] = 'キャンセル';
        $strings['Add'] = '追加';
        $strings['Name'] = '名前';
        $strings['Yes'] = 'はい';
        $strings['No'] = 'いいえ';
        $strings['FirstNameRequired'] = '名(first name)を入力してください。';
        $strings['LastNameRequired'] = '姓(last name)を入力してください。';
        $strings['PwMustMatch'] = '新しいパスワード欄とパスワード確認欄は同じものを入力してください。';
        $strings['ValidEmailRequired'] = '有効なメールアドレスを入力してください。';
        $strings['UniqueEmailRequired'] = 'そのメールアドレスはすでに登録されています。';
        $strings['UniqueUsernameRequired'] = 'そのユーザー名はすでに登録されています。';
        $strings['UserNameRequired'] = 'ユーザー名は必須です。';
        $strings['CaptchaMustMatch'] = 'セキュリティ画像の文字を正確に入力してください。';
        $strings['Today'] = '今日';
        $strings['Week'] = '週間';
        $strings['Month'] = '月間';
        $strings['BackToCalendar'] = 'カレンダーに戻る';
        $strings['BeginDate'] = '開始';
        $strings['EndDate'] = '終了';
        $strings['Username'] = 'ユーザー名';
        $strings['Password'] = 'パスワード';
        $strings['PasswordConfirmation'] = 'パスワード確認';
        $strings['DefaultPage'] = 'デフォルトページ';
        $strings['MyCalendar'] = 'マイ カレンダー';
        $strings['ScheduleCalendar'] = 'スケジュール カレンダー';
        $strings['Registration'] = '登録(Registration)';
        $strings['NoAnnouncements'] = 'お知らせはありません';
        $strings['Announcements'] = 'お知らせ';
        $strings['NoUpcomingReservations'] = 'ありません';
        $strings['UpcomingReservations'] = '近日中の予約';
        $strings['AllNoUpcomingReservations'] = '予約できます';
        $strings['AllUpcomingReservations'] = '近日中の予約(全体)';
        $strings['ShowHide'] = '表示/非表示';
        $strings['Error'] = 'エラー';
        $strings['ReturnToPreviousPage'] = '直近のページへ戻る';
        $strings['UnknownError'] = '不明なエラー';
        $strings['InsufficientPermissionsError'] = 'このリソースを操作する権限がありません';
        $strings['MissingReservationResourceError'] = 'リソースが選択されていません';
        $strings['MissingReservationScheduleError'] = 'スケジュールが選択されていません';
        $strings['DoesNotRepeat'] = 'なし';
        $strings['Daily'] = '日ごと';
        $strings['Weekly'] = '週ごと';
        $strings['Monthly'] = '月ごと';
        $strings['Yearly'] = '年ごと';
        $strings['RepeatPrompt'] = '繰り返し単位';
        $strings['hours'] = '時間';
        $strings['days'] = '日ごと';
        $strings['weeks'] = '週ごと';
        $strings['months'] = 'か月ごと';
        $strings['years'] = '年ごと';
        $strings['day'] = '日';
        $strings['week'] = '週';
        $strings['month'] = 'か月';
        $strings['year'] = '年';
        $strings['repeatDayOfMonth'] = '同じ日付';
        $strings['repeatDayOfWeek'] = '同じ曜日';
        $strings['RepeatUntilPrompt'] = 'まで';
        $strings['RepeatEveryPrompt'] = ' ';
        $strings['RepeatDaysPrompt'] = ' ';
        $strings['CreateReservationHeading'] = '予約の作成';
        $strings['EditReservationHeading'] = '予約の変更 %s';
        $strings['ViewReservationHeading'] = '予約の表示 %s';
        $strings['ReservationErrors'] = '予約の変更';
        $strings['Create'] = '作成';
        $strings['ThisInstance'] = 'この回だけ';
        $strings['AllInstances'] = 'すべての回';
        $strings['FutureInstances'] = 'この回から先';
        $strings['Print'] = '印刷';
        $strings['ShowHideNavigation'] = '3か月カレンダーから表示範囲を選択';
        $strings['ReferenceNumber'] = '照会番号';
        $strings['Tomorrow'] = '明日';
        $strings['LaterThisWeek'] = '今週(明後日以後)';
        $strings['NextWeek'] = '翌週';
        $strings['SignOut'] = 'サインアウト';
        $strings['LayoutDescription'] = '一度に %s から、 %s 日間を表示';
        $strings['AllResources'] = '全てのリソース';
        $strings['TakeOffline'] = 'オフラインにする';
        $strings['BringOnline'] = 'オンラインにする';
        $strings['AddImage'] = '画像を添付';
        $strings['NoImage'] = '画像なし';
        $strings['Move'] = '移動する';
        $strings['AppearsOn'] = '表示するのは %s';
        $strings['Location'] = '場所';
        $strings['NoLocationLabel'] = '-';
        $strings['Contact'] = '連絡先';
        $strings['NoContactLabel'] = '-';
        $strings['Description'] = '説明';
        $strings['NoDescriptionLabel'] = '-';
        $strings['Notes'] = '備考';
        $strings['NoNotesLabel'] = '-';
        $strings['NoTitleLabel'] = '-';
        $strings['UsageConfiguration'] = '運用規則';
        $strings['ChangeConfiguration'] = '設定変更';
        $strings['ResourceMinLength'] = '最短 %s';
        $strings['ResourceMinLengthNone'] = '予約の最短時間の制限なし';
        $strings['ResourceMaxLength'] = '最長 %s';
        $strings['ResourceMaxLengthNone'] = '予約の最長時間の制限なし';
        $strings['ResourceRequiresApproval'] = '予約には承認が必要';
        $strings['ResourceRequiresApprovalNone'] = '承認なしで予約可能';
        $strings['ResourcePermissionAutoGranted'] = '新規ユーザーも利用可能';
        $strings['ResourcePermissionNotAutoGranted'] = '新規ユーザーは個別に利用許可が必要';
        $strings['ResourceMinNotice'] = '開始時刻よりも %s 前に予約が必要';
        $strings['ResourceMinNoticeNone'] = '現在時刻から予約可能';
        $strings['ResourceMinNoticeUpdate'] = '予約は開始時刻の %s 前まで変更可能';
        $strings['ResourceMinNoticeNoneUpdate'] = '現時刻でも予約を変更可能';
        $strings['ResourceMinNoticeDelete'] = '予約は開始時刻の %s 前まで削除可能';
        $strings['ResourceMinNoticeNoneDelete'] = '現時刻でも予約を削除可能';
        $strings['ResourceMaxNotice'] = '終了時刻が現在時刻より %s 先の予約は不可';
        $strings['ResourceMaxNoticeNone'] = '予約の終了時刻に制限なし';
        $strings['ResourceBufferTime'] = '予約の時間間隔 %s ';
        $strings['ResourceBufferTimeNone'] = '予約の時間間隔は不要';
        $strings['ResourceAllowMultiDay'] = '日をまたいで予約可能';
        $strings['ResourceNotAllowMultiDay'] = '日をまたいで予約不可';
        $strings['ResourceCapacity'] = '%s 人まで';
        $strings['ResourceCapacityNone'] = '人数制限なし';
        $strings['AddNewResource'] = '新規リソースの追加';
        $strings['AddNewUser'] = '新規ユーザーの追加';
        $strings['AddResource'] = 'リソース追加';
        $strings['Capacity'] = '人数制限';
        $strings['Access'] = 'アクセス';
        $strings['Duration'] = '期間';
        $strings['Active'] = '有効';
        $strings['Inactive'] = '無効';
        $strings['ResetPassword'] = 'パスワードをリセット';
        $strings['LastLogin'] = '直近のログイン';
        $strings['Search'] = '検索';
        $strings['ResourcePermissions'] = 'リソース利用権限';
        $strings['Reservations'] = '予約';
        $strings['Groups'] = 'グループ';
        $strings['Users'] = 'ユーザー';
        $strings['AllUsers'] = '全ユーザー';
        $strings['AllGroups'] = '全グループ';
        $strings['AllSchedules'] = '全スケジュール';
        $strings['UsernameOrEmail'] = 'ユーザー名またはメールアドレス';
        $strings['Members'] = 'メンバー';
        $strings['QuickSlotCreation'] = '予約枠を %s 分毎に %s から %s まで生成する';
        $strings['ApplyUpdatesTo'] = '更新を適用するのは';
        $strings['CancelParticipation'] = '出席を取り消す';
        $strings['Attending'] = '出席しますか';
        $strings['QuotaConfiguration'] = '%s の %s において %s のユーザーは %s %s を１ %s 内の上限とする。';
        $strings['QuotaEnforcement'] = '対象 %s %s';
        $strings['reservations'] = '予約';
        $strings['reservation'] = '予約';
        $strings['ChangeCalendar'] = 'カレンダーを変更';
        $strings['AddQuota'] = '量の制限を追加';
        $strings['FindUser'] = 'ユーザーを見つける';
        $strings['Created'] = '作成日時';
        $strings['LastModified'] = '最終更新日時';
        $strings['GroupName'] = 'グループ名';
        $strings['GroupMembers'] = 'グループメンバー';
        $strings['GroupRoles'] = 'グループの役割';
        $strings['GroupAdmin'] = 'グループ管理者';
        $strings['Actions'] = '操作';
        $strings['CurrentPassword'] = '現在のパスワード';
        $strings['NewPassword'] = '新しいパスワード';
        $strings['InvalidPassword'] = 'パスワードが間違っています';
        $strings['PasswordChangedSuccessfully'] = 'パスワードは変更されました';
        $strings['SignedInAs'] = 'サインイン中 ';
        $strings['NotSignedIn'] = 'サインインしていません';
        $strings['ReservationTitle'] = '件名';
        $strings['ReservationDescription'] = '説明';
        $strings['ResourceList'] = 'リソース・リスト';
        $strings['Accessories'] = '備品';
        $strings['InvitationList'] = '招待者リスト';
        $strings['AccessoryName'] = '備品名称';
        $strings['QuantityAvailable'] = '数量';
        $strings['Resources'] = 'リソース';
        $strings['Participants'] = '出席者';
        $strings['User'] = 'ユーザー';
        $strings['Resource'] = 'リソース';
        $strings['Status'] = '状態';
        $strings['Approve'] = '承認';
        $strings['Page'] = 'ページ';
        $strings['Rows'] = '件数';
        $strings['Unlimited'] = '制限なし';
        $strings['Email'] = 'メール';
        $strings['EmailAddress'] = 'メールアドレス';
        $strings['Phone'] = '電話';
        $strings['Organization'] = '所属';
        $strings['Position'] = '役職';
        $strings['Language'] = '言語';
        $strings['Permissions'] = '権限';
        $strings['Reset'] = 'リセット';
        $strings['FindGroup'] = 'グループを探す';
        $strings['Manage'] = '管理';
        $strings['None'] = 'なし';
        $strings['AddToOutlook'] = 'Outlookに追加';
        $strings['Done'] = '決定';
        $strings['RememberMe'] = 'サインインを維持する';
        $strings['FirstTimeUser?'] = '初めてですか?';
        $strings['CreateAnAccount'] = 'アカウントを作成する';
        $strings['ViewSchedule'] = 'スケジュールを見る';
        $strings['ForgotMyPassword'] = 'パスワードを忘れました';
        $strings['YouWillBeEmailedANewPassword'] = 'ランダムに生成されたパスワードがメールで送られます';
        $strings['Close'] = '閉じる';
        $strings['ExportToCSV'] = 'CSVで出力する';
        $strings['OK'] = 'OK';
        $strings['Working'] = '作業中';
        $strings['Login'] = 'ログイン';
        $strings['AdditionalInformation'] = '追加情報';
        $strings['AllFieldsAreRequired'] = '全ての項目を入力してください';
        $strings['Optional'] = '任意';
        $strings['YourProfileWasUpdated'] = 'プロフィールを更新しました';
        $strings['YourSettingsWereUpdated'] = '設定を更新しました';
        $strings['Register'] = '登録';
        $strings['SecurityCode'] = 'セキュリティコード';
        $strings['ReservationCreatedPreference'] = '自分または誰かが代理で予約をしたとき';
        $strings['ReservationUpdatedPreference'] = '自分または誰かが代理で予約を変更したとき';
        $strings['ReservationDeletedPreference'] = '自分または誰かが代理で予約を取り消したとき';
        $strings['ReservationApprovalPreference'] = '自分の予約が承認されたとき';
        $strings['PreferenceSendEmail'] = 'メールで通知する';
        $strings['PreferenceNoEmail'] = '通知しない';
        $strings['ReservationCreated'] = '予約しました！';
        $strings['ReservationUpdated'] = '予約を変更しました！';
        $strings['ReservationRemoved'] = '予約を取り消しました';
        $strings['ReservationRequiresApproval'] = '予約されたリソースには承認が必要なものがあります。これらの予約は承認されるまで保留されます。';
        $strings['YourReferenceNumber'] = '照会番号： %s';
        $strings['ChangeUser'] = 'ユーザーを変更';
        $strings['MoreResources'] = '別のリソースも予約';
        $strings['ReservationLength'] = '予約期間';
        $strings['ParticipantList'] = '出席者一覧';
        $strings['AddParticipants'] = '出席者追加';
        $strings['InviteOthers'] = '招待する';
        $strings['AddResources'] = 'リソースを追加';
        $strings['AddAccessories'] = '備品を追加';
        $strings['Accessory'] = '備品';
        $strings['QuantityRequested'] = '数量を指定してください';
        $strings['CreatingReservation'] = '予約しています';
        $strings['UpdatingReservation'] = '予約を変更しています';
        $strings['DeleteWarning'] = 'この操作は取り消しできません!';
        $strings['DeleteAccessoryWarning'] = 'この備品は全ての予約からも削除されます。';
        $strings['AddAccessory'] = '備品を追加する';
        $strings['AddBlackout'] = '利用不能時間を追加';
        $strings['AllResourcesOn'] = 'スケジュール中の全リソースを対象';
        $strings['Reason'] = '理由';
        $strings['BlackoutShowMe'] = '利用不能時間の予約を表示する';
        $strings['BlackoutDeleteConflicts'] = '利用不能時間の予約は削除する';
        $strings['Filter'] = 'フィルター';
        $strings['Between'] = '期間';
        $strings['CreatedBy'] = '作成者';
        $strings['BlackoutCreated'] = '利用不能時間を設定しました';
        $strings['BlackoutNotCreated'] = '利用不能時間を設定しませんでした';
        $strings['BlackoutUpdated'] = '利用不能時間を更新しました';
        $strings['BlackoutNotUpdated'] = '利用不能時間を更新しませんでした';
        $strings['BlackoutConflicts'] = '利用不能時間が重なっています';
        $strings['ReservationConflicts'] = '予約が重なっています';
        $strings['UsersInGroup'] = 'このグループのユーザー';
        $strings['Browse'] = '表示';
        $strings['DeleteGroupWarning'] = 'グループを削除するとグループによって与えられていた許可も削除されます。このグループのユーザーがリソースにアクセスできなくなることがあります。';
        $strings['WhatRolesApplyToThisGroup'] = 'このグループの役割';
        $strings['WhoCanManageThisGroup'] = 'このグループの管理者';
        $strings['WhoCanManageThisSchedule'] = 'このスケジュールの管理者';
        $strings['AllQuotas'] = '全ての量制限';
        $strings['QuotaReminder'] = '注意: 量の制限はそれぞれのスケジュールのタイムゾーンによって計算します。';
        $strings['AllReservations'] = '全ての予約';
        $strings['PendingReservations'] = '保留中の予約';
        $strings['Approving'] = '認証中';
        $strings['MoveToSchedule'] = 'スケジュールへ移動';
        $strings['DeleteResourceWarning'] = 'このリソースを削除すると関連する次のものを含む全てのデータも削除されます。';
        $strings['DeleteResourceWarningReservations'] = '当リソースの全ての予約';
        $strings['DeleteResourceWarningPermissions'] = '当リソースの全ての権限の割り当て';
        $strings['DeleteResourceWarningReassign'] = '消したくないものについては、操作を行う前に再割り当てしてください。';
        $strings['ScheduleLayout'] = '時間枠設定 (時間は %s)';
        $strings['ReservableTimeSlots'] = '予約可能な時間枠';
        $strings['BlockedTimeSlots'] = '予約できない時間枠';
        $strings['ThisIsTheDefaultSchedule'] = 'これはデフォルトスケジュールです';
        $strings['DefaultScheduleCannotBeDeleted'] = 'デフォルトスケジュールは削除できません';
        $strings['MakeDefault'] = 'デフォルトにする';
        $strings['BringDown'] = 'Bring Down';
        $strings['ChangeLayout'] = '時間枠の変更';
        $strings['AddSchedule'] = 'スケジュールを追加';
        $strings['StartsOn'] = '週の最初の曜日';
        $strings['NumberOfDaysVisible'] = '表示する日数';
        $strings['UseSameLayoutAs'] = '時間枠を流用';
        $strings['Format'] = '書式';
        $strings['OptionalLabel'] = 'ラベル(なくても可)';
        $strings['LayoutInstructions'] = '1行に1件の時間枠を記入してください。 時間枠は1日の最初から最後(12:00 AM)までの24時間全てを網羅するようにしてください。';
        $strings['AddUser'] = 'ユーザーを追加';
        $strings['UserPermissionInfo'] = '実際のリソース利用権限は、ユーザーの役割、グループでの許可、その他の許可設定によって変わってきます。';
        $strings['DeleteUserWarning'] = 'このユーザーを削除すると、現在、未来、過去の予約も削除することになります。';
        $strings['AddAnnouncement'] = 'お知らせの追加';
        $strings['Announcement'] = 'お知らせ';
        $strings['Priority'] = '優先度';
        $strings['Reservable'] = '予約可';
        $strings['Unreservable'] = '使用不可';
        $strings['Reserved'] = '予約済';
        $strings['MyReservation'] = '自分の予約';
        $strings['Pending'] = '保留中';
        $strings['Past'] = '過去の予約';
        $strings['Restricted'] = '予約不可';
        $strings['ViewAll'] = '全て表示';
        $strings['MoveResourcesAndReservations'] = 'リソースと予約を移動';
        $strings['TurnOffSubscription'] = 'カレンダーの購読を禁止する';
        $strings['TurnOnSubscription'] = 'このカレンダーの購読を許可する';
        $strings['SubscribeToCalendar'] = 'このカレンダーを購読する';
        $strings['SubscriptionsAreDisabled'] = '管理者がカレンダーの購読を無効にしています';
        $strings['NoResourceAdministratorLabel'] = '管理者なし';
        $strings['WhoCanManageThisResource'] = 'このリソースの管理権限を誰に付与しますか？';
        $strings['ResourceAdministrator'] = 'リソース管理者';
        $strings['Private'] = 'プライベート';
        $strings['Accept'] = '受理';
        $strings['Decline'] = '辞退';
        $strings['ShowFullWeek'] = '1週間すべてを表示';
        $strings['CustomAttributes'] = 'カスタム属性';
        $strings['AddAttribute'] = '属性を追加';
        $strings['EditAttribute'] = '属性を更新';
        $strings['DisplayLabel'] = 'ラベルを表示';
        $strings['Type'] = '型';
        $strings['Required'] = '必須';
        $strings['ValidationExpression'] = '検証評価式';
        $strings['PossibleValues'] = '可能な値';
        $strings['SingleLineTextbox'] = '一行のテキストボックス';
        $strings['MultiLineTextbox'] = '複数行のテキストボックス';
        $strings['Checkbox'] = 'チェックボックス';
        $strings['SelectList'] = '選択リスト';
        $strings['CommaSeparated'] = 'カンマ(,)区切り';
        $strings['Category'] = 'カテゴリー';
        $strings['CategoryReservation'] = '予約';
        $strings['CategoryGroup'] = 'グループ';
        $strings['SortOrder'] = '並び替え順';
        $strings['Title'] = '件名';
        $strings['AdditionalAttributes'] = '追加属性';
        $strings['True'] = '真(True)';
        $strings['False'] = '偽(False)';
        $strings['ForgotPasswordEmailSent'] = 'パスワードをリセットする方法を書いたeメールを送信しました。';
        $strings['ActivationEmailSent'] = 'アクティベートのためのeメールが、すぐに届くはずです。';
        $strings['AccountActivationError'] = '申し訳ありません。あなたのアカウントをアクティベート出来ませんでした。';
        $strings['Attachments'] = '添付';
        $strings['AttachFile'] = 'ファイルを添付する';
        $strings['Maximum'] = '最大';
        $strings['NoScheduleAdministratorLabel'] = 'スケジュールの管理者ではない';
        $strings['ScheduleAdministrator'] = 'スケジュールの管理者';
        $strings['Total'] = '合計';
        $strings['QuantityReserved'] = '予約された数量';
        $strings['AllAccessories'] = '全ての備品';
        $strings['GetReport'] = 'レポートを作成';
        $strings['NoResultsFound'] = '該当するものがありません';
        $strings['SaveThisReport'] = 'このレポートを保存';
        $strings['ReportSaved'] = 'レポートを保存しました!';
        $strings['EmailReport'] = 'レポートをメールで送る';
        $strings['ReportSent'] = 'レポートを送信しました!';
        $strings['RunReport'] = 'レポートを実行';
        $strings['NoSavedReports'] = '保存されているレポートはありません。';
        $strings['CurrentWeek'] = '今週';
        $strings['CurrentMonth'] = '今月';
        $strings['AllTime'] = '全期間';
        $strings['FilterBy'] = '以下の条件で';
        $strings['Select'] = '選択';
        $strings['List'] = 'リスト';
        $strings['TotalTime'] = '合計時間';
        $strings['Count'] = '回数';
        $strings['Usage'] = '使用量';
        $strings['AggregateBy'] = '集計項目';
        $strings['Range'] = '期間';
        $strings['Choose'] = '選択';
        $strings['All'] = '全て';
        $strings['ViewAsChart'] = 'グラフで表示';
        $strings['ReservedResources'] = '予約されているリソース';
        $strings['ReservedAccessories'] = '予約されている備品';
        $strings['ResourceUsageTimeBooked'] = 'リソース使用量 - 予約時間';
        $strings['ResourceUsageReservationCount'] = 'リソース使用量 - 予約回数';
        $strings['Top20UsersTimeBooked'] = '上位 20 人 - 予約時間';
        $strings['Top20UsersReservationCount'] = '上位 20 人 - 予約回数';
        $strings['ConfigurationUpdated'] = '設定ファイルが更新されました。';
        $strings['ConfigurationUiNotEnabled'] = 'このページにはアクセスできません。 $conf[\'settings\'][\'pages\'][\'enable.configuration\'] が false になっているか、設定されていないかです。';
        $strings['ConfigurationFileNotWritable'] = '設定ファイルが書き込み禁止になっています。ファイルのパーミッションを確認し、やり直してみてください。';
        $strings['ConfigurationUpdateHelp'] = '設定の<a target=_blank href=%s>ヘルプファイル</a>を参照';
        $strings['GeneralConfigSettings'] = '設定';
        $strings['UseSameLayoutForAllDays'] = 'すべての曜日で同じレイアウトを使う';
        $strings['LayoutVariesByDay'] = '曜日ごとにレイアウトを変える';
        $strings['ManageReminders'] = 'リマインダー管理';
        $strings['ReminderUser'] = 'ユーザーID';
        $strings['ReminderMessage'] = 'メッセージ';
        $strings['ReminderAddress'] = 'アドレス';
        $strings['ReminderSendtime'] = '送信時刻';
        $strings['ReminderRefNumber'] = '予約参照数(Reservation Reference Number)';
        $strings['ReminderSendtimeDate'] = 'リマインダー日';
        $strings['ReminderSendtimeTime'] = 'リマインダー時刻(HH:MM)';
        $strings['ReminderSendtimeAMPM'] = 'AM / PM';
        $strings['AddReminder'] = 'リマインダーを追加';
        $strings['DeleteReminderWarning'] = '間違いありませんか？';
        $strings['NoReminders'] = 'リマインダーはありません。';
        $strings['Reminders'] = 'リマインダー';
        $strings['SendReminder'] = 'リマインダー送信';
        $strings['minutes'] = '分';
        $strings['hours'] = '時間';
        $strings['days'] = '日';
        $strings['ReminderBeforeStart'] = '開始時刻まで';
        $strings['ReminderBeforeEnd'] = '終了時刻まで';
        $strings['Logo'] = 'ロゴ';
        $strings['CssFile'] = 'CSS ファイル';
        $strings['ThemeUploadSuccess'] = '変更を保存しました。変更を反映するためにページをリロードしてください。';
        $strings['MakeDefaultSchedule'] = 'このスケジュールをデフォルトにする';
        $strings['DefaultScheduleSet'] = 'このスケジュールがデフォルトになっています';
        $strings['FlipSchedule'] = 'スケジュールのレイアウトを切り替える';
        $strings['Next'] = '次へ';
        $strings['Success'] = '成功';
        $strings['Participant'] = '出席者';
        $strings['ResourceFilter'] = 'リソースフィルター';
        $strings['ResourceGroups'] = 'リソースグループ';
        $strings['AddNewGroup'] = '新規グループ作成';
        $strings['Quit'] = '終了';
        $strings['AddGroup'] = 'グループ追加';
        $strings['StandardScheduleDisplay'] = '標準表示を使用';
        $strings['TallScheduleDisplay'] = '縦長表示を使用';
        $strings['WideScheduleDisplay'] = '幅広表示を使用';
        $strings['CondensedWeekScheduleDisplay'] = '圧縮した週表示を使用';
        $strings['ResourceGroupHelp1'] = 'ドラッグ＆ドロップで配置を変更。';
        $strings['ResourceGroupHelp2'] = '右クリックで更なる操作。';
        $strings['ResourceGroupHelp3'] = 'ドラッグ＆ドロップでリソースをグループに追加。';
        $strings['ResourceGroupWarning'] = 'リソースグループを使用する場合、それぞれのリソースは少なくとも一つのグループに割り当てられていなくてはなりません。割り当てられていないリソースは予約することが出来ません。';
        $strings['ResourceType'] = 'リソースタイプ';
        $strings['AppliesTo'] = '右記へ適用';
        $strings['UniquePerInstance'] = '個別に識別';
        $strings['AddResourceType'] = 'リソースタイプを追加';
        $strings['NoResourceTypeLabel'] = '(リソースタイプ無し)';
        $strings['ClearFilter'] = 'リセット';
        $strings['MinimumCapacity'] = '最少利用人数';
        $strings['Color'] = '色';
        $strings['Available'] = 'あり';
        $strings['Unavailable'] = 'なし';
        $strings['Hidden'] = '隠蔽';
        $strings['ResourceStatus'] = 'リソースの状態';
        $strings['CurrentStatus'] = '現在の状態';
        $strings['AllReservationResources'] = '全て予約リソース';
        $strings['File'] = 'ファイル';
        $strings['BulkResourceUpdate'] = 'リソースの一括更新';
        $strings['Unchanged'] = '無変更';
        $strings['Common'] = '共通';
        $strings['AdminOnly'] = '管理者のみ';
        $strings['AdvancedFilter'] = 'アドバンスドフィルター';
        $strings['MinimumQuantity'] = '最小';
        $strings['MaximumQuantity'] = '最大';
        $strings['ChangeLanguage'] = '言語を変更';
        $strings['AddRule'] = '条件を追加';
        $strings['Attribute'] = '属性';
        $strings['RequiredValue'] = '条件値';
        $strings['ReservationCustomRuleAdd'] = '条件値： %s 予約の色：';
        $strings['AddReservationColorRule'] = '予約の背景色を変更';
        $strings['LimitAttributeScope'] = '特定の場合のみ取得';
        $strings['CollectFor'] = '次の予約時：';
        $strings['SignIn'] = 'サインイン';
        $strings['AllParticipants'] = '全ての参加者';
        $strings['RegisterANewAccount'] = '新しいアカウントを登録する';
        $strings['Dates'] = '日付';
        $strings['More'] = 'さらに表示';
        $strings['ResourceAvailability'] = '使用可能なリソース';
        $strings['UnavailableAllDay'] = '使用できません';
        $strings['AvailableUntil'] = '次の時間まで利用可能 … ';
        $strings['AvailableBeginningAt'] = '次の時間から利用可能 … ';
        $strings['AvailableAt'] = '次の時間に利用可能 … ';
        $strings['AllResourceTypes'] = '全てのリソースのタイプ';
        $strings['AllResourceStatuses'] = '全てのリソースの状態';
        $strings['AllowParticipantsToJoin'] = '招待者以外の参加を許可';
        $strings['Join'] = '参加';
        $strings['YouAreAParticipant'] = 'あなたはこの予約の参加者です。';
        $strings['YouAreInvited'] = 'あなたはこの予約に招待されています。';
        $strings['YouCanJoinThisReservation'] = 'あなたはこの予約に参加できます。';
        $strings['Import'] = 'データ入力';
        $strings['GetTemplate'] = 'テンプレートを取得';
        $strings['UserImportInstructions'] = '<ul><li>ファイルはCSV形式である必要があります。</li><li>ユーザー名とメールアドレスは必須です。</li><li>他のフィールドは任意です。</li><li>他のフィールドを空白のままにすると、デフォルト値として\'password\'がユーザーのパスワードとして設定されます。</li><li>例として提供されたテンプレートを使用します。</li></ul>';
        $strings['RowsImported'] = 'インポートした行';
        $strings['RowsSkipped'] = 'スキップした行';
        $strings['Columns'] = 'カラム';
        $strings['Reserve'] = '予約する';
        $strings['AllDay'] = '全ての時間';
        $strings['Everyday'] = '全ての日';
        $strings['IncludingCompletedReservations'] = '完了した予約もカウント対象にする';
        $strings['NotCountingCompletedReservations'] = '未完了の予約はカウント対象にしない';
        $strings['RetrySkipConflicts'] = '競合する予約をスキップ';
        $strings['Retry'] = 'リトライ';
        $strings['RemoveExistingPermissions'] = '既存の権限を削除しますか？';
        $strings['Continue'] = '次へ';
        $strings['WeNeedYourEmailAddress'] = '予約するにはメールアドレスが必要です';
        $strings['ResourceColor'] = 'リソースの色';
        $strings['DateTime'] = '時刻';
        $strings['AutoReleaseNotification'] = '%s 分以内にチェックインがない場合は予約を解除';
        $strings['RequiresCheckInNotification'] = 'チェックイン／アウトが必要';
        $strings['NoCheckInRequiredNotification'] = 'チェックイン／アウトは不要';
        $strings['RequiresApproval'] = '承認が必要';
        $strings['CheckingIn'] = 'チェックインしています';
        $strings['CheckingOut'] = 'チェックアウトしています';
        $strings['CheckIn'] = 'チェックイン';
        $strings['CheckOut'] = 'チェックアウト';
        $strings['ReleasedIn'] = '予約解除まで';
        $strings['CheckedInSuccess'] = 'チェックイン済';
        $strings['CheckedOutSuccess'] = 'チェックアウト済';
        $strings['CheckInFailed'] = 'あなたはチェックインできません';
        $strings['CheckOutFailed'] = 'あなたはチェックアウトできません';
        $strings['CheckInTime'] = 'チェックイン時刻';
        $strings['CheckOutTime'] = 'チェックアウト時刻';
        $strings['OriginalEndDate'] = '終了予定時刻';
        $strings['SpecificDates'] = '選択した日付を表示';
        $strings['Users'] = 'ユーザー';
        $strings['Guest'] = '部外の招待者';
        $strings['ResourceDisplayPrompt'] = '表示するリソース';
        $strings['Credits'] = 'クレジット';
        $strings['AvailableCredits'] = '利用可能なクレジット';
        $strings['CreditUsagePerSlot'] = 'スロットごとに %s クレジットが必要（オフピーク）';
        $strings['PeakCreditUsagePerSlot'] = 'スロットごとに %s クレジットが必要（ピーク）';
        $strings['CreditsRule'] = '十分なクレジットがありません。 必要なクレジット：%s,  アカウントのクレジット：%s';
        $strings['PeakTimes'] = 'ピーク時間';
        $strings['AllYear'] = '通年';
        $strings['MoreOptions'] = '追加オプション';
        $strings['More'] = '操作メニュー';
        $strings['SendAsEmail'] = 'メール送信';
        $strings['UsersInGroups'] = 'グループに所属するユーザー';
        $strings['UsersWithAccessToResources'] = 'リソースを利用するユーザー';
        $strings['AnnouncementSubject'] = '%s が新しいお知らせを投稿しました';
        $strings['AnnouncementEmailNotice'] = '名にメールで送信されます';
        $strings['Day'] = '日';
        $strings['NotifyWhenAvailable'] = '利用可能になったら通知する';
        $strings['AddingToWaitlist'] = 'あなたを待機リストに追加しています';
        $strings['WaitlistRequestAdded'] = 'この時間が利用可能になると通知されます';
        $strings['PrintQRCode'] = 'QRコードを印刷';
        $strings['FindATime'] = '空き時間を探す';
        $strings['AnyResource'] = '全てのリソース';
        $strings['ThisWeek'] = '今週';
        $strings['Hours'] = '時間';
        $strings['Minutes'] = '分';
        $strings['ImportICS'] = 'ICS インポート';
        $strings['ImportQuartzy'] = 'Quartzy インポート';
        $strings['OnlyIcs'] = 'icsファイルをアップロード可能';
        $strings['IcsLocationsAsResources'] = '場所はリソースとしてインポートされます';
        $strings['IcsMissingOrganizer'] = '主催者がいないイベントは、現在のユーザーに所有者が設定されます';
        $strings['IcsWarning'] = '予約ルールは適用されません - 競合、重複などが生じます';
        $strings['BlackoutAroundConflicts'] = '衝突した予約はブラックアウトさせる';
        $strings['DuplicateReservation'] = 'コピーして新規登録';
        $strings['UnavailableNow'] = '現在利用できません';
        $strings['ReserveLater'] = '後で予約';
        $strings['CollectedFor'] = '取得対象';
        $strings['IncludeDeleted'] = '取り消された予約を含む';
        $strings['Deleted'] = '削除済み';
        $strings['Back'] = '戻る';
        $strings['Forward'] = '次へ';
        $strings['DateRange'] = '期間を指定';
        $strings['Copy'] = 'コピー';
        $strings['Detect'] = '見つける';
        $strings['Autofill'] = 'オートフィル';
        $strings['NameOrEmail'] = '名前またはメールアドレス';
        $strings['ImportResources'] = 'リソースをインポート';
        $strings['ExportResources'] = 'リソースを出力';
        $strings['ResourceImportInstructions'] = '<ul><li>ファイルはUTF-8エンコーディングのCSV形式である必要があります。</li><li>名前は必須フィールドです。他のフィールドを空白のままにすると、デフォルト値が設定されます。</li><li>ステータスオプションは、「使用可能」,「使用不可」,「非表示」です。</li><li>色は16進値である必要があります。 例）#ffffff</li><li>自動割り当て列と承認列はtrueまたはfalseです。</li><li>属性の有効性は適用されません。</li><li>複数のリソースグループをコンマで区切ります。</li><li>例としてテンプレートを示します。</li></ul>';
        $strings['ReservationImportInstructions'] = '<ul><li>ファイルはUTF-8エンコーディングのCSV形式である必要があります。</li><li>メール、リソース名、開始、終了は必須フィールドです。</li><li>開始と終了には完全な日付時刻が必要です。推奨形式はYYYY-mm-dd HH：mm（2017-12-31 20:30）です。</li><li>ルール、競合、および有効なタイムスロットはチェックされません。</li><li>通知</li><li>属性の有効性は強制されません。</li><li>複数のリソース名をコンマで区切ります。</li><li>例としてテンプレートを示します。</li></ul>';
        $strings['AutoReleaseMinutes'] = '自動リリースまで (分)';
        $strings['CreditsPeak'] = 'クレジット（ピーク）';
        $strings['CreditsOffPeak'] = 'クレジット（オフピーク）';
        $strings['ResourceMinLengthCsv'] = '予約の最短時間';
        $strings['ResourceMaxLengthCsv'] = '予約の最長時間';
        $strings['ResourceBufferTimeCsv'] = 'バッファ時間';
        $strings['ResourceMinNoticeAddCsv'] = '予約した際に最小限の通知';
        //        $strings['ResourceMinNoticeAddCsv'] = 'Reservation Add Minimum Notice';
        $strings['ResourceMinNoticeUpdateCsv'] = '予約を変更した際に最小限の通知';
        //        $strings['ResourceMinNoticeUpdateCsv'] = 'Reservation Update Minimum Notice';
        $strings['ResourceMinNoticeDeleteCsv'] = '予約を取り消した際に最小限の通知';
        //        $strings['ResourceMinNoticeDeleteCsv'] = 'Reservation Delete Minimum Notice';
        $strings['ResourceMaxNoticeCsv'] = '予約の最長';
        //        $strings['ResourceMaxNoticeCsv'] = 'Reservation Maximum End';
        $strings['Export'] = 'データ出力';
        $strings['DeleteMultipleUserWarning'] = 'これらのユーザーを削除すると、現在、将来、および過去の予約がすべて削除されます。 メールは送信されません。 ';
        $strings['DeleteMultipleReservationsWarning'] = 'メールは送信されません。';
        $strings['ErrorMovingReservation'] = '予約の移動エラー';
        $strings['SelectUser'] = 'ユーザーの選択';
        $strings['InviteUsers'] = '招待する';
        $strings['InviteUsersLabel'] = '招待する人のメールアドレスを入力してください';
        $strings['ApplyToCurrentUsers'] = '現在のユーザーに適用';
        $strings['ReasonText'] = '理由';
        $strings['NoAvailableMatchingTimes'] = '検索に一致する利用可能な時間はありません';
        $strings['Schedules'] = 'スケジュール';
        $strings['NotifyUser'] = 'ユーザーに通知';
        $strings['UpdateUsersOnImport'] = 'メールアドレスが既に存在する場合、既存のユーザーを更新します';
        $strings['UpdateResourcesOnImport'] = '名前がすでに存在する場合、既存のリソースを更新します';
        $strings['Reject'] = '却下';
        $strings['CheckingAvailability'] = '利用可能か確認中';
        $strings['CreditPurchaseNotEnabled'] = 'クレジットを購入する機能を有効にしていません';
        $strings['CreditsCost'] = '各クレジット費用';
        $strings['Currency'] = '通貨';
        $strings['PayPalClientId'] = 'クライアントID';
        $strings['PayPalSecret'] = '秘匿';

        $strings['PayPalEnvironment'] = '環境';
        $strings['Sandbox'] = 'Sandbox';
        $strings['Live'] = 'Live';
        $strings['StripePublishableKey'] = '公開鍵';
        $strings['StripeSecretKey'] = '秘密鍵';
        $strings['CreditsUpdated'] = 'クレジット費用が変更されました';
        $strings['GatewaysUpdated'] = '支払ゲートウェイが更新されました';
        $strings['PurchaseSummary'] = '支払明細';
        $strings['EachCreditCosts'] = '各クレジット費用';
        $strings['Checkout'] = 'チェックアウト';
        $strings['Quantity'] = '個数';
        $strings['CreditPurchase'] = 'クレジット購入';
        $strings['EmptyCart'] = 'カートは空です';
        $strings['BuyCredits'] = 'クレジット購入';
        $strings['CreditsPurchased'] = 'クレジットを購入しました';
        $strings['ViewYourCredits'] = 'クレジットを表示';
        $strings['TryAgain'] = '再試行';
        $strings['PurchaseFailed'] = '支払いの処理中に問題が発生しました';
        $strings['NoteCreditsPurchased'] = '購入したクレジット';
        $strings['CreditsUpdatedLog'] = '%s が更新したクレジット';
        $strings['ReservationCreatedLog'] = '予約が作成されました。参照番号 %s ';
        $strings['ReservationUpdatedLog'] = '予約が変更されました。参照番号 %s ';
        $strings['ReservationDeletedLog'] = '予約が取り消されました。参照番号 %s ';
        $strings['BuyMoreCredits'] = 'クレジットを追加購入';

        $strings['Transactions'] = '取引';
        $strings['Cost'] = '費用';
        $strings['PaymentGateways'] = '支払ゲートウェイ';
        $strings['CreditHistory'] = '信用履歴';
        $strings['TransactionHistory'] = '取引履歴';
        $strings['Date'] = '日付';
        $strings['Note'] = '備考';
        $strings['CreditsBefore'] = '以前のクレジット';
        $strings['CreditsAfter'] = 'クレジット後';
        $strings['TransactionFee'] = '取引手数料';
        $strings['InvoiceNumber'] = '請求書番号';
        $strings['TransactionId'] = 'トランザクションID';
        $strings['Gateway'] = 'ゲートウェイ';
        $strings['GatewayTransactionDate'] = 'ゲートウェイ取引日付';
        $strings['Refund'] = '払い戻し';
        $strings['IssueRefund'] = '払い戻し';
        $strings['RefundIssued'] = '返金が正常に発行されました';
        $strings['RefundAmount'] = '返金額';
        $strings['AmountRefunded'] = '返金済';
        $strings['FullyRefunded'] = '全額返金';

        $strings['YourCredits'] = 'あなたのクレジット';
        $strings['PayWithCard'] = 'カードで支払う';
        $strings['or'] = 'または';
        $strings['CreditsRequired'] = 'クレジットが必要';
        $strings['AddToGoogleCalendar'] = 'Googleカレンダーに追加';
        $strings['Image'] = '画像';
        $strings['ChooseOrDropFile'] = 'ファイルを選択／ここにドラッグ';
        $strings['SlackBookResource'] = '[予約しました] %s';
        $strings['SlackBookNow'] = '今すぐ予約';
        $strings['SlackNotFound'] = 'その名前のリソースが見つかりませんでした。改めて予約をしてください。';
        $strings['AutomaticallyAddToGroup'] = 'このグループに新しいユーザーを自動的に追加します';
        $strings['GroupAutomaticallyAdd'] = '自動追加';
        $strings['TermsOfService'] = '利用規約';
        $strings['EnterTermsManually'] = '規約を入力してください';
        $strings['LinkToTerms'] = '規約へのリンク';
        $strings['UploadTerms'] = '規約のアップロード';
        $strings['RequireTermsOfServiceAcknowledgement'] = '利用規約の確認が必要';
        $strings['UponReservation'] = '予約時';
        $strings['UponRegistration'] = '登録時';
        $strings['ViewTerms'] = '利用規約を表示';

        $strings['IAccept'] = '了解しました';
        $strings['TheTermsOfService'] = '利用規約';
        $strings['DisplayPage'] = 'ページを表示';
        $strings['AvailableAllYear'] = '通年';
        $strings['Availability'] = '利用可能期間';
        $strings['AvailableBetween'] = '期間　';
        $strings['ConcurrentYes'] = 'リソースは一度に複数人で予約可能';
        $strings['ConcurrentNo'] = 'リソースは一度に複数人で予約不可';
        $strings['ScheduleAvailabilityEarly'] = 'このスケジュールはまだ利用できません。利用可能時期；';
        $strings['ScheduleAvailabilityLate'] = 'このスケジュールはもう利用できません。利用可能時期；';
        $strings['ResourceImages'] = 'リソース画像';
        $strings['FullAccess'] = 'フルアクセス';
        $strings['ViewOnly'] = '表示のみ';
        $strings['Purge'] = '全削除';
        $strings['UsersWillBeDeleted'] = 'ユーザーは削除されます';
        $strings['BlackoutsWillBeDeleted'] = 'ブラックアウト時間は削除されます';
        $strings['ReservationsWillBePurged'] = '予約は削除されます';
        $strings['ReservationsWillBeDeleted'] = '予約は削除されます';
        $strings['PermanentlyDeleteUsers'] = '設定日以降にログインしていないユーザーを完全に削除';
        $strings['DeleteBlackoutsBefore'] = '設定日以前のブラックアウト時間を削除';
        $strings['DeletedReservations'] = '削除済みの予約';
        $strings['DeleteReservationsBefore'] = '設定日以前の予約を削除';
        $strings['SwitchToACustomLayout'] = 'カスタムレイアウトに切り替える';
        $strings['SwitchToAStandardLayout'] = '標準レイアウトに切り替える';
        $strings['ThisScheduleUsesACustomLayout'] = 'このスケジュールはカスタムレイアウトを使用します';
        $strings['ThisScheduleUsesAStandardLayout'] = 'このスケジュールは標準レイアウトを使用します';
        $strings['SwitchLayoutWarning'] = 'レイアウトタイプを変更してもよろしいですか？これにより、既存のすべてのスロットが削除されます。 ';
        $strings['DeleteThisTimeSlot'] = 'このタイムスロットを削除しますか？';
        $strings['Refresh'] = 'リフレッシュ';
        $strings['ViewReservation'] = '予約を表示';
        $strings['PublicId'] = 'パブリックID';
        $strings['Public'] = 'パブリック';
        $strings['AtomFeedTitle'] = ' %s 予約';
        $strings['DefaultStyle'] = 'デフォルトスタイル';
        $strings['Standard'] = '標準';
        $strings['Wide'] = '幅広表示';
        $strings['Tall'] = '縦長表示';
        $strings['EmailTemplate'] = 'メールテンプレート';
        $strings['SelectEmailTemplate'] = 'メールテンプレートの選択';
        $strings['ReloadOriginalContents'] = '元のコンテンツをリロード';
        $strings['UpdateEmailTemplateSuccess'] = '更新されたメールテンプレート';
        $strings['UpdateEmailTemplateFailure'] = 'メールテンプレートを更新できませんでした。ディレクトリが書き込み可能であることを確認してください。';
        $strings['BulkResourceDelete'] = '一括リソース削除';
        $strings['NewVersion'] = '新バージョンです！';
        $strings['WhatsNew'] = 'Whats New？';
        $strings['OnlyViewedCalendar'] = 'このスケジュールは、カレンダービューで表示できます';
        $strings['Grid'] = 'グリッド表示';
        $strings['List'] = 'リスト表示';
        $strings['NoReservationsFound'] = '予約が見つかりません';
        $strings['EmailReservation'] = 'メール予約';
        $strings['AdHocMeeting'] = '突発的なミーティング';
        $strings['NextReservation'] = '次の予約';
        $strings['MissedCheckin'] = '逃したチェックイン';
        $strings['MissedCheckout'] = '逃したチェックアウト';
        $strings['Utilization'] = '利用';
        $strings['SpecificTime'] = '特定の時間';
        $strings['ReservationSeriesEndingPreference'] = '定期的な一括予約が終了するとき';
        $strings['NotAttending'] = '参加していません';
        $strings['ViewAvailability'] = '利用可能状況';
        $strings['ReservationDetails'] = '予約に戻る';
        $strings['StartTime'] = '開始時間';
        $strings['EndTime'] = '終了時間';
        $strings['New'] = 'New';
        $strings['Updated'] = 'Updated';
        // End Strings

        // Install
        $strings['InstallApplication'] = 'LibreBookingをインストール ( MySQLのみ )';
        $strings['IncorrectInstallPassword'] = '申し訳ありませんが、パスワードが違っています。';
        $strings['SetInstallPassword'] = 'インストールを実行する前に、インストールパスワードを設定しておかなくてはなりません。';
        $strings['InstallPasswordInstructions'] = '%s 内の %s にランダムで推測できないようなパスワードを設定して、再度このページに戻って来てください。<br/> %s を使ってもいいでしょう。';
        $strings['NoUpgradeNeeded'] = 'アップグレードの必要はありません。インストールプロセスを実行すると、すべてのデータが消えてしまいます。';
        $strings['ProvideInstallPassword'] = 'インストールパスワードを入力してください。';
        $strings['InstallPasswordLocation'] = '%s という項目が %s の中にあるのがパスワードです。';
        $strings['VerifyInstallSettings'] = '続ける前に下記のデフォルト値を確認してください。もしくは %s 内を変更してください。';
        $strings['DatabaseName'] = 'データベース名';
        $strings['DatabaseUser'] = 'データベースのユーザー名';
        $strings['DatabaseHost'] = 'データベースのあるホスト名';
        $strings['DatabaseCredentials'] = 'MySQLにデータベースを作成できるユーザーの資格情報が必要です。もし分からない場合は、データベース管理者に問い合わせてください。通常、root なら大丈夫でしょう。';
        $strings['MySQLUser'] = 'MySQL ユーザー';
        $strings['InstallOptionsWarning'] = 'ここからのオプションはホスティング環境ではうまくいかないことが多いでしょう。もしホスティング環境へインストールする場合は、MySQL操作ツールを使って、以下の操作を完了してください。';
        $strings['CreateDatabase'] = 'データベース作成';
        $strings['CreateDatabaseUser'] = 'データベースユーザー作成';
        $strings['PopulateExampleData'] = 'サンプルデータをインポートする。管理者アカウント: admin/password と一般ユーザーアカウント: user/password を作成する。';
        $strings['DataWipeWarning'] = '警告: この操作は全てのデータを削除します。';
        $strings['RunInstallation'] = 'インストールを実行';
        $strings['UpgradeNotice'] = 'バージョン <b>%s</b> からバージョン <b>%s</b> へアップグレードします。';
        $strings['RunUpgrade'] = 'アップグレードの実行';
        $strings['Executing'] = '実行中';
        $strings['StatementFailed'] = '失敗 詳細:';
        $strings['SQLStatement'] = 'SQL 文:';
        $strings['ErrorCode'] = 'エラーコード:';
        $strings['ErrorText'] = 'エラーテキスト:';
        $strings['InstallationSuccess'] = 'インストールは無事完了しました！';
        $strings['RegisterAdminUser'] = '管理者アカウントを作成してください。サンプルデータをインポートしていない場合は必須です。 %s 内で $conf[\'settings\'][\'allow.self.registration\'] = \'true\' にしておいてください。';
        $strings['LoginWithSampleAccounts'] = 'サンプルデータをインポートした場合は、admin/password で管理者、user/password で一般ユーザーとしてログインできます。';
        $strings['InstalledVersion'] = '実行中のphpScheduleItのバージョンは %s です';
        $strings['InstallUpgradeConfig'] = '設定ファイルをアップグレードしてください。';
        $strings['InstallationFailure'] = 'インストール中に問題が発生しました。問題箇所を修正し再度インストールを実行してください。';
        $strings['ConfigureApplication'] = 'phpScheduleItの設定';
        $strings['ConfigUpdateSuccess'] = '設定ファイルは更新されました！';
        $strings['ConfigUpdateFailure'] = '設定ファイルを自動で更新できませんでした。config.php を下記の内容で上書きしてください。';
        $strings['ScriptUrlWarning'] = '<em> script.url</em>の設定が正しくない可能性があります。 現在は <strong>%s</strong> です。 <strong>%s</strong> である必要があります。';
        // End Install

        // Errors
        $strings['LoginError'] = 'ユーザー名またはパスワードが一致しません';
        $strings['ReservationFailed'] = '予約できませんでした';
        $strings['MinNoticeError'] = 'このリソースが今から予約できるのは %s 以降です。';
        $strings['MinNoticeErrorUpdate'] = 'この予約を変更するには事前の通知が必要です。 %s 以前の予約は変更できません。';
        $strings['MinNoticeErrorDelete'] = 'この予約を取り消すには事前の通知が必要です。 %s 以前の予約は取り消しできません。';
        $strings['MaxNoticeError'] = '予約可能な時間になっていないため、このリソースは予約できません。 %s までの予約できます。';
        $strings['MinDurationError'] = '予約は %s 以上にしてください';
        $strings['MaxDurationError'] = '予約は %s 未満にしてください';
        $strings['ConflictingAccessoryDates'] = '備品が希望数に足りません:';
        $strings['NoResourcePermission'] = 'リソースを使用する権限がありません';
        $strings['ConflictingReservationDates'] = '次の日時で予約が重なっています:';
        $strings['StartDateBeforeEndDateRule'] = '開始日時を終了よりも前にしてください。';
        $strings['StartIsInPast'] = '開始時刻を過ぎていいます。';
        $strings['EmailDisabled'] = '管理者がメールでの通知を無効にしています。';
        $strings['ValidLayoutRequired'] = '時間枠は一日の最初から最後(12:00 AM)までの24時間全てを網羅するようにしてください。';
        $strings['CustomAttributeErrors'] = '次の誤りがあります';
        $strings['CustomAttributeRequired'] = '「%s」を入力してください';
        $strings['CustomAttributeInvalid'] = '「%s」の入力値が無効です';
        $strings['AttachmentLoadingError'] = '指定されたファイルを読み込む際に問題が発生しました。ご迷惑をおかけします。';
        $strings['InvalidAttachmentExtension'] = 'アップロードできるファイル種類 : %s';
        $strings['InvalidStartSlot'] = '予約の始まり日時が無効です。';
        $strings['InvalidEndSlot'] = '予約の終わり日時が無効です。';
        $strings['MaxParticipantsError'] = '%s の定員は %s 名です';
        $strings['ReservationCriticalError'] = '予約データ保存時にエラーが発生しました。このエラーが何度も起きるときは管理者に連絡してください。';
        $strings['InvalidStartReminderTime'] = '予約開始のリマインダー時間が無効です';
        $strings['InvalidEndReminderTime'] = '予約終了のリマインダー時間が無効です';
        $strings['QuotaExceeded'] = '予約量の制限を越えています';
        $strings['MultiDayRule'] = '%s 日をまたいだ予約は出来ません';
        $strings['InvalidReservationData'] = '予約リクエストに問題があります';
        $strings['PasswordError'] = 'パスワードは %s 文字以上で、%s 文字以上の数字を含む必要があります。';
        $strings['PasswordErrorRequirements'] = 'パスワードには %s 文字以上の大文字と小文字のアルファベット、%s 文字以上の数字が必要です。';
        $strings['NoReservationAccess'] = 'この予約の変更は許可されていません。';

        $strings['PasswordControlledExternallyError'] = 'あなたのパスワードは外部システムによって制御しているため、ここでは更新できません';
        $strings['AccessoryResourceRequiredErrorMessage'] = 'アクセサリ %s はリソース %s でのみ予約できます';
        $strings['AccessoryMinQuantityErrorMessage'] = '少なくとも %s のアクセサリ %s を予約する必要があります';
        $strings['AccessoryMaxQuantityErrorMessage'] = ' %s 以上のアクセサリ %s を予約できません';
        $strings['AccessoryResourceAssociationErrorMessage'] = '要求されたリソースでアクセサリ 『 %s 』 を予約できません';
        $strings['NoResources'] = 'リソースを追加していません。';
        $strings['ParticipationNotAllowed'] = 'この予約への参加は許可されていません';
        $strings['ReservationCannotBeCheckedInTo'] = 'この予約はチェックインできません';
        $strings['ReservationCannotBeCheckedOutFrom'] = 'この予約はチェックアウトできません';
        $strings['InvalidEmailDomain'] = 'そのメールアドレスは許可されたドメインのものではありません';
        $strings['TermsOfServiceError'] = '利用規約に同意する必要があります';
        $strings['UserNotFound'] = 'そのユーザーが見つかりませんでした';
        $strings['ScheduleAvailabilityError'] = 'このスケジュールは %s 〜 %s に利用できます';
        $strings['ReservationNotFoundError'] = '予約が見つかりません';
        $strings['ReservationNotAvailable'] = '予約できません';
        $strings['TitleRequiredRule'] = '件名が必要です';
        $strings['DescriptionRequiredRule'] = '予約の説明が必要です';
        $strings['WhatCanThisGroupManage'] = 'このグループでは何を管理しますか？';
        $strings['ReservationParticipationActivityPreference'] = '誰かが自分の予約に参加したとき、または予約を離れたとき';
        $strings['RegisteredAccountRequired'] = '登録されたユーザーのみ予約できます';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = '予約の作成';
        $strings['EditReservation'] = '予約の編集';
        $strings['LogIn'] = 'ログイン';
        $strings['ManageReservations'] = '予約';
        $strings['AwaitingActivation'] = 'アクティベーション待ち';
        $strings['PendingApproval'] = '保留中の承認';
        $strings['ManageSchedules'] = 'スケジュール管理';
        $strings['ManageResources'] = 'リソース';
        $strings['ManageAccessories'] = '備品';
        $strings['ManageUsers'] = 'ユーザー';
        $strings['ManageGroups'] = 'グループ';
        $strings['ManageQuotas'] = '制限';
        $strings['ManageBlackouts'] = '利用不能時間';
        $strings['MyDashboard'] = 'マイ ダッシュボード';
        $strings['ServerSettings'] = 'サーバー設定';
        $strings['Dashboard'] = 'ダッシュボード';
        $strings['Help'] = 'ヘルプ';
        $strings['Administration'] = '設定管理';
        $strings['About'] = 'このアプリについて';
        $strings['Bookings'] = '予約状況';
        $strings['Schedule'] = 'スケジュール';
        $strings['Account'] = 'アカウント';
        $strings['EditProfile'] = 'プロフィール編集';
        $strings['FindAnOpening'] = '出欠未決を検索';
        $strings['OpenInvitations'] = 'まだ返答していない招待';
        $strings['ResourceCalendar'] = 'リソースカレンダー';
        $strings['Reservation'] = '新規予約';
        $strings['Install'] = 'インストール';
        $strings['ChangePassword'] = 'パスワード変更';
        $strings['MyAccount'] = 'マイ アカウント';
        $strings['Profile'] = 'プロフィール';
        $strings['ApplicationManagement'] = '管理';
        $strings['ForgotPassword'] = 'パスワードを忘れました';
        $strings['NotificationPreferences'] = '通知設定';
        $strings['ManageAnnouncements'] = 'お知らせ';
        $strings['Responsibilities'] = '責任';
        $strings['GroupReservations'] = 'グループ予約';
        $strings['ResourceReservations'] = 'リソース予約';
        $strings['Customization'] = 'カスタマイズ';
        $strings['Attributes'] = '属性';
        $strings['AccountActivation'] = 'アカウントのアクティベート';
        $strings['ScheduleReservations'] = 'スケジュールの予約';
        $strings['Reports'] = 'レポート';
        $strings['GenerateReport'] = '新しいレポートを作る';
        $strings['MySavedReports'] = '保存されたレポート';
        $strings['CommonReports'] = '汎用のレポート';
        $strings['ViewDay'] = '日表示';
        $strings['Group'] = 'グループ';
        $strings['ManageConfiguration'] = 'アプリケーション設定';
        $strings['LookAndFeel'] = '外観デザイン';
        $strings['ManageResourceGroups'] = 'リソースグループ';
        $strings['ManageResourceTypes'] = 'リソースタイプ';
        $strings['ManageResourceStatus'] = 'リソースの状態';
        $strings['ReservationColors'] = '予約の背景色';
        $strings['SearchReservations'] = '予約の検索';

        $strings['ManagePayments'] = '支払';
        $strings['ViewCalendar'] = 'カレンダーを表示';
        $strings['DataCleanup'] = 'データクリーンアップ';
        $strings['ManageEmailTemplates'] = 'メールテンプレートの管理';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = '日';
        $strings['DayMondaySingle'] = '月';
        $strings['DayTuesdaySingle'] = '火';
        $strings['DayWednesdaySingle'] = '水';
        $strings['DayThursdaySingle'] = '木';
        $strings['DayFridaySingle'] = '金';
        $strings['DaySaturdaySingle'] = '土';

        $strings['DaySundayAbbr'] = '日';
        $strings['DayMondayAbbr'] = '月';
        $strings['DayTuesdayAbbr'] = '火';
        $strings['DayWednesdayAbbr'] = '水';
        $strings['DayThursdayAbbr'] = '木';
        $strings['DayFridayAbbr'] = '金';
        $strings['DaySaturdayAbbr'] = '土';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = '予約が承認されました';
        $strings['ReservationCreatedSubject'] = '予約されました';
        $strings['ReservationUpdatedSubject'] = '予約が変更されました';
        $strings['ReservationDeletedSubject'] = '予約は取り消されました';
        $strings['ReservationCreatedAdminSubject'] = '通知：予約作成';
        $strings['ReservationUpdatedAdminSubject'] = '通知：予約変更';
        $strings['ReservationDeleteAdminSubject'] = '通知：予約取り消し';
        $strings['ReservationApprovalAdminSubject'] = '通知：予約には承認が必要です';
        $strings['ParticipantAddedSubject'] = '出席登録のお知らせ';
        $strings['ParticipantDeletedSubject'] = '予約は取り消されました';
        $strings['InviteeAddedSubject'] = '参加のお願い';
        $strings['ResetPassword'] = 'パスワードリセット要求';
        $strings['ActivateYourAccount'] = 'アカウントをアクティベートしてください';
        $strings['ReportSubject'] = 'レポート送付 (%s)';
        $strings['ReservationStartingSoonSubject'] = '予約 %s は間もなく始まります';
        $strings['ReservationEndingSoonSubject'] = '予約 %s は間もなく終了します';
        $strings['UserAdded'] = 'ユーザーが追加されました。';
        $strings['UserDeleted'] = 'ユーザー : %s のアカウントは %s によって削除されました';
        $strings['GuestAccountCreatedSubject'] = 'ゲストアカウント %s 詳細';
        $strings['AccountCreatedSubject'] = 'アカウント %s 詳細';
        $strings['InviteUserSubject'] = '%s があなたを %s に招待しています';

        $strings['ReservationApprovedSubjectWithResource'] = '%s の予約が承認されました';
        $strings['ReservationCreatedSubjectWithResource'] = '%s に作成された予約';
        $strings['ReservationUpdatedSubjectWithResource'] = '%s の予約更新';
        $strings['ReservationDeletedSubjectWithResource'] = '%s の予約が取り消されました';
        $strings['ReservationCreatedAdminSubjectWithResource'] = '通知：%sの予約が作成されました';
        $strings['ReservationUpdatedAdminSubjectWithResource'] = '通知：%sの予約が更新されました';
        $strings['ReservationDeleteAdminSubjectWithResource'] = '通知：%sの予約が取り消されました';
        $strings['ReservationApprovalAdminSubjectWithResource'] = '通知：%sの予約には承認が必要です';
        $strings['ParticipantAddedSubjectWithResource'] = '%s は %s の予約にあなたを追加しました';
        $strings['ParticipantDeletedSubjectWithResource'] = '%s は %s の予約を取り消しました';
        $strings['InviteeAddedSubjectWithResource'] = '%s が %s の予約に招待しました';
        $strings['MissedCheckinEmailSubject'] = '%s のチェックインに失敗しました';
        $strings['ReservationShareSubject'] = '%s は %s の予約を共有しました';
        $strings['ReservationSeriesEndingSubject'] = '%s の定期予約は %s で終了しています';
        $strings['ReservationParticipantAccept'] = '%s は %s ( %s ) の予約への招待を受け入れました';
        $strings['ReservationParticipantDecline'] = '%s は %s ( %s ) の予約招待を拒否しました';
        $strings['ReservationParticipantJoin'] = '%s は %s ( %s ) の予約に参加しました';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = '過去の予約はありません';
        $strings['PastReservations'] = '過去の予約';
        $strings['AllNoPastReservations'] = '直近%s日間に過去の予約はありません';
        $strings['AllPastReservations'] = 'すべての過去の予約';
        $strings['Yesterday'] = '昨日';
        $strings['EarlierThisWeek'] = '今週の前半';
        $strings['PreviousWeek'] = '先週';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'あなたのグループには今後の予約がありません';
        $strings['GroupUpcomingReservations'] = '私のグループの今後の予約';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'Facebookでログイン中にエラーが発生しました。もう一度お試しください。';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = '承認待ちの予約はありません';
        $strings['PendingApprovalReservations'] = '承認待ちの予約';
        $strings['LaterThisMonth'] = '今月の後で';
        $strings['LaterThisYear'] = '今年の後で';
        $strings['Remaining'] = '残り';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard

        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'この予約に含まれるいずれかのリソースへの権限がないため、予約の詳細を表示できません';
        //End Schedule Resource Permissions
        //END NEEDS CHECKING


        $this->Strings = $strings;

        return $this->Strings;
    }

    /**
     * @return array
     */
    protected function _LoadDays()
    {
        $days = parent::_LoadDays();

        /***
        DAY NAMES
        All of these arrays MUST start with Sunday as the first element
        and go through the seven day week, ending on Saturday
         ***/
        // The full day name
        $days['full'] = ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'];
        // The three letter abbreviation
        $days['abbr'] = ['日', '月', '火', '水', '木', '金', '土'];
        // The two letter abbreviation
        $days['two'] = ['日', '月', '火', '水', '木', '金', '土'];
        // The one letter abbreviation
        $days['letter'] = ['日', '月', '火', '水', '木', '金', '土'];

        $this->Days = $days;

        return $this->Days;
    }

    /**
     * @return array
     */
    protected function _LoadMonths()
    {
        $months = parent::_LoadMonths();

        /***
        MONTH NAMES
        All of these arrays MUST start with January as the first element
        and go through the twelve months of the year, ending on December
         ***/
        // The full month name
        $months['full'] = ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'];
        // The three letter month name
        $months['abbr'] = ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'];

        $this->Months = $months;

        return $this->Months;
    }

    /**
     * @return array
     */
    protected function _LoadLetters()
    {
        $this->Letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

        return $this->Letters;
    }

    protected function _GetHtmlLangCode()
    {
        return 'ja';
    }
}
