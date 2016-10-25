<?php
/***********************************************************************
 * This Source Code Form is subject to the terms of the Mozilla Public *
 * License, v. 2.0. If a copy of the MPL was not distributed with this *
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.            *
 ***********************************************************************/

ini_set('default_charset', 'utf-8');
define('DATETIME', 'Y-m-d g:T');
define('DATETIMESIMPLE', 'Y-m-d H:i');
define('DATESIMPLE', 'Y-m-d');

$lang = array(
    'Home'=>'홈',
    'RiskManagement'=>'위험 관리',
    'Reporting'=>'보고',
    'Configure'=>'구성',
    'MyProfile'=>'내 프로파일',
    'Logout'=>'로그 아웃',
    'LogInHere'=>'여기에 로그인',
    'Username'=>'사용자 이름',
    'Password'=>'비밀번호',
    'ForgotYourPassword'=>'비밀번호',
    'Login'=>'로그인',
    'Reset'=>'Reset',
    'Send'=>'보내기',
    'Update'=>'업데이트',
    'SendPasswordResetEmail'=>'비밀번호 재설정 메일 보내기',
    'PasswordReset'=>'비밀번호 재설정',
    'ResetToken'=>'Reset 토큰',
    'RepeatPassword'=>'반복 비밀번호',
    'Submit'=>'제출',
    'ProfileDetails'=>'프로필 정보',
    'LastLogin'=>'마지막 로그인',
    'ChangePassword'=>'비밀번호 변경',
    'CurrentPassword'=>'현재의 비밀번호',
    'NewPassword'=>'새로운 비밀번호',
    'ConfirmPassword'=>'비밀번호 확인',
    'ConfigureRiskFormula'=>'위험성학식',
    'ConfigureReviewSettings'=>'구성 설정 검토',
    'AddAndRemoveValues'=>'추가 및 제거 값',
    'UserManagement'=>'사용자 관리',
    'RedefineNamingConventions'=>'이름 규칙 정의',
    'AuditTrail'=>'감사',
    'Extras'=>'엑스트라',
    'Announcements'=>'공지사항',
    'About'=>'대',
    'Impact'=>'영향',
    'Likelihood'=>'가능성',
    'MitigationEffort'=>'완화 노력',
    'Change'=>'변경',
    'to'=>'하기',
    'AddANewUser'=>'새 사용자 추가',
    'Type'=>'유형',
    'FullName'=>'이름',
    'EmailAddress'=>'E-mail Address',
    'Teams'=>'Team(s)',
    'ALL'=>'모든',
    'NONE'=>'없음',
    'UserResponsibilities'=>'사용자 책임',
    'AbleToSubmitNewRisks'=>'를 제출할 수 있는 새로운 위험',
    'AbleToModifyExistingRisks'=>'수정할 수 있는 기존 위험',
    'AbleToCloseRisks'=>'닫을 수 있을 위험',
    'AbleToPlanMitigations'=>'를 계획할 수 있 완화',
    'AbleToReviewLowRisks'=>'을 검토할 수 있는 낮은 위험성',
    'AbleToReviewMediumRisks'=>'을 검토할 수 있 매체험',
    'AbleToReviewHighRisks'=>'을 검토할 수 있게 높은 위험성',
    'AllowAccessToConfigureMenu'=>'한 액세스를 허용하는"구성"메뉴',
    'MultiFactorAuthentication'=>'다단계 인증',
    'None'=>'없음',
    'Add'=>'추가',
    'ViewDetailsForUser'=>'에 대한 세부정보의 사용자',
    'DetailsForUser'=>'상세정보를 위한 사용자',
    'Select'=>'선택',
    'EnableAndDisableUsers'=>'화 및 사용자',
    'EnableAndDisableUsersHelp'=>'이 기능을 사용하여 활성화 또는 비활성화 사용자 로그인 유지하면서 감사의 흔적 활동 사용자',
    'DisableUser'=>'사용자',
    'Disable'=>'사',
    'EnableUser'=>'사용자',
    'Enable'=>'사',
    'DeleteAnExistingUser'=>'삭제하려면 기존 사용자',
    'DeleteCurrentUser'=>'현재 사용자 삭제',
    'Delete'=>'삭제',
    'SendPasswordResetEmailForUser'=>'보 암호를 재설정 이메일에 대한 사용자',
    'Category'=>'카테고리',
    'AddNewCategoryNamed'=>'새 범주 추가라는 이름',
    'DeleteCurrentCategoryNamed'=>'현재 카테고리를 삭제라는 이름',
    'Team'=>'팀',
    'AddNewTeamNamed'=>'새로운 추가 팀이라는 이름',
    'DeleteCurrentTeamNamed'=>'삭제재 팀이라는 이름',
    'Technology'=>'기술',
    'AddNewTechnologyNamed'=>'추가로 새로운 기술이라는 이름',
    'DeleteCurrentTechnologyNamed'=>'삭제 현재의 기술 이름',
    'SiteLocation'=>'사이트/위치',
    'AddNewSiteLocationNamed'=>'추가로 새로운 사이트 또는 위치라는 이름',
    'DeleteCurrentSiteLocationNamed'=>'삭제는 현재 사이트/라는 이름의 장소',
    'ControlRegulation'=>'컨트롤 규정',
    'AddNewControlRegulationNamed'=>'추가 새로운 규정 제어라',
    'DeleteCurrentControlRegulationNamed'=>'삭제는 현재 관리 규정된',
    'RiskPlanningStrategy'=>'위험 전략 계획',
    'AddNewRiskPlanningStrategyNamed'=>'추가 새로운 위험 전략 계획 이름',
    'DeleteCurrentRiskPlanningStrategyNamed'=>'삭제는 현재의 위험 전략 계획 이름',
    'CloseReason'=>'가까운 이유',
    'AddNewCloseReasonNamed'=>'추가로 새로운 가까운 이유는 이름',
    'DeleteCurrentCloseReasonNamed'=>'삭제 현재 가까운 이유는 이름',
    'IWantToReviewHighRiskEvery'=>'고 싶을 검토하는 위험이 높은 모든',
    'IWantToReviewMediumRiskEvery'=>'고 싶을 검토 중 위험 모',
    'IWantToReviewLowRiskEvery'=>'고 싶을 검토하는 위험이 낮은 모든',
    'days'=>'일',
    'MyClassicRiskFormulaIs'=>'내 클래식한 위험 수식',
    'RISK'=>'위험',
    'IConsiderHighRiskToBeAnythingGreaterThan'=>'을 고려 높은 위험을 아무것도 보다 큰',
    'IConsiderMediumRiskToBeLessThanAboveButGreaterThan'=>'생각 매체에 위험이 적다는 것이지만,그보다 크',
    'IConsiderlowRiskToBeLessThanAboveButGreaterThan'=>'내가 고려한 위험이 낮은 것을보다 위에 있지만,보다 큰',
    'HighRisk'=>'높은 위험',
    'MediumRisk'=>'중간험',
    'LowRisk'=>'저렴한 위험',
    'Irrelevant'=>'관련',
    'SubmitYourRisks'=>'출의 위험',
    'PlanYourMitigations'=>'계획의 완화',
    'PerformManagementReviews'=>'을 수행 관리 리뷰',
    'PrioritizeForProjectPlanning'=>'의 우선 순위를 위해 프로젝트 계획',
    'ReviewRisksRegularly'=>'검토하고 위험에 정기적으로',
    'DocumentANewRisk'=>'문서 새로운 위험',
    'UseThisFormHelp'=>'이 양식을 사용하기 위해서 문서를 새로 위험한 배려에서 위험 관리 프로세스',
    'Subject'=>'주제',
    'ExternalReferenceId'=>'외부 참조 ID',
    'ControlNumber'=>'제어 번호',
    'Owner'=>'자',
    'OwnersManager'=>'자 관리',
    'RiskScoringMethod'=>'위험 채점 방법',
    'CurrentLikelihood'=>'현재 가능성',
    'CurrentImpact'=>'현재의 영향',
    'RiskAssessment'=>'위험 평가',
    'AdditionalNotes'=>'추가 사항',
    'UNREVIEWED'=>'검토되지 않은',
    'PASTDUE'=>'지난로',
    'ID'=>'ID',
    'Status'=>'상태',
    'Risk'=>'위험',
    'DaysOpen'=>'일',
    'CalculatedRisk'=>'계산험',
    'SubmittedBy'=>'에 의해 제출된',
    'NextReviewDate'=>'다음 날',
    'CVSSRiskScoring'=>'CVSS 위험성 평가',
    'DREADRiskScoring'=>'공 위험성 평가',
    'OWASPRiskScoring'=>'OWASP 위험성 평가',
    'CustomRiskScoring'=>'사용자 지정 위험을 득점',
    'MitigationPlanningHelp'=>'아래의 목록을 제출하는 위험을 완화를 요구 계획',
    'ManagementReviewHelp'=>'아래의 목록을 제출하가 필요한 위험 관리 검토',
    'Submitted'=>'제출',
    'MitigationPlanned'=>'완화 조치 계획',
    'ManagementReview'=>'관리 검토',
    'No'=>'No',
    'Yes'=>'네',
    'AddAndRemoveProjects'=>'추가 및 제거 프로젝트',
    'AddAndRemoveProjectsHelp'=>'추가 및 제거하기 위해 프로젝트를 연결하는 여러 함께 위험에 대한 우선 순위 지정',
    'AddNewProjectNamed'=>'추가로 새로운 프로젝트 명',
    'DeleteCurrentProjectNamed'=>'삭제하는 현재 라는 프로젝트',
    'AddUnassignedRisksToProjects'=>'추가 할당되지 않은 위험 프로젝트',
    'AddUnassignedRisksToProjectsHelp'=>'드래그 앤 드롭되지 않은 위험을 표시에 대한 고려사항으로 프로젝트에 적합한 프로젝트 탭',
    'PrioritizeProjects'=>'프로젝트 우선 순위 지정',
    'PrioritizeProjectsHelp'=>'이동 프로젝트 및 순서 변경의 우선 순위입니다. 한 번 완료하는 것을 잊지 마세요,누르면"업데이트"버튼을 변경 사항을 저장합',
    'SaveRisksToProjects'=>'저장험 프로젝트',
    'RiskId'=>'위험 ID',
    'RiskActions'=>'위험 작업',
    'ReopenRisk'=>'다시험',
    'CloseRisk'=>'닫 위험',
    'EditRisk'=>'위험 편집',
    'PlanAMitigation'=>'계획을 완화',
    'PerformAReview'=>'을 수행 검토',
    'AddAComment'=>'추가 코멘트',
    'ShowRiskScoringDetails'=>'쇼 위험성 평가 정보',
    'HideRiskScoringDetails'=>'숨기 위험 점수 정보',
    'Details'=>'상세정보',
    'SubmissionDate'=>'제출한 날짜',
    'DateSubmitted'=>'날짜',
    'EditDetails'=>'정보 편집',
    'Mitigation'=>'완화',
    'MitigationDate'=>'완화 Date',
    'PlanningStrategy'=>'기획 전략',
    'CurrentSolution'=>'현재 솔루션',
    'SecurityRequirements'=>'보안 요구사항',
    'SecurityRecommendations'=>'보안 권장 사항',
    'EditMitigation'=>'편집을 완화',
    'LastReview'=>'마지막 검토',
    'ReviewDate'=>'리뷰 날짜',
    'Reviewer'=>'검토',
    'Review'=>'리뷰',
    'NextStep'=>'다음 단계',
    'Comments'=>'댓글',
    'ViewAllReviews'=>'모두 보기 리뷰',
    'ReviewHistory'=>'검사',
    'Comment'=>'Comment',
    'ClassicRiskScoring'=>'적 위험성 평가',
    'RiskScoringActions'=>'위험 점수 작업',
    'UpdateClassicScore'=>'업데이트 클래식 점수',
    'ScoreBy'=>'점수',
    'RISKClassicExp1'=>'위험=(능 x 영향+2(충격))',
    'RISKClassicExp2'=>'위험=(능 x 영향+격)',
    'RISKClassicExp3'=>'위험=(능 x 격)',
    'RISKClassicExp4'=>'위험=(능 x 에 미치는 영향 가능성)',
    'RISKClassicExp5'=>'위험=(능 x 영향+2(가능성))',
    'Reason'=>'이',
    'CloseOutInformation'=>'Close-정보',
    'SubmitManagementReview'=>'제출 Management 검토',
    'SubmitRiskMitigation'=>'출 위험을 완화',
    'RiskDashboard'=>'위험드',
    'RiskTrend'=>'위험 트렌드',
    'AllOpenRisksAssignedToMeByRiskLevel'=>'열려있는 모든 위험을 할당하여 위험 수준',
    'AllOpenRisksByRiskLevel'=>'열려있는 모든 위험에 의해 위험 수준',
    'AllOpenRisksConsideredForProjectsByRiskLevel'=>'열려있는 모든 위험에 대해 고려하여 프로젝트 위험 수준',
    'AllOpenRisksAcceptedUntilNextReviewByRiskLevel'=>'열려있는 모든 위험을 받을 때까지 다음을 검토하여 위험 수준',
    'AllOpenRisksToSubmitAsAProductionIssueByRiskLevel'=>'열려있는 모든 위험을 제출으로 생산하여 문제의 위험 수준',
    'AllOpenRisksByScoringMethod'=>'열려있는 모든 위험에 의해 득점 방법',
    'AllClosedRisksByRiskLevel'=>'모두 닫히는 위험에 의해 위험 수준',
    'SubmittedRisksByDate'=>'제출된 위험에 의해 날짜',
    'MitigationsByDate'=>'완화에 의해 날짜',
    'ManagementReviewsByDate'=>'관리 리뷰 날짜',
    'ProjectsAndRisksAssigned'=>'프로젝트 위험을 할당',
    'OpenRisks'=>'위험 오픈',
    'ClosedRisks'=>'위험 폐쇄',
    'ReportMyOpenHelp'=>'이 보고서에는 모든 위험이 있는 현재 사용자로 소유자 또는 관리자와 관련된 위험을 주문해서 위험 수준',
    'ReportOpenHelp'=>'이 보고서에는 모든 위험을 주문해서 위험 수준',
    'ReportProjectsHelp'=>'이 보고서는 모든 열 위험으로 간주 프로젝트를 위한 주문에 의해 위험 수준',
    'ReportNextReviewHelp'=>'이 보고서에는 모든 위험을 받을 때까지 다음 주문을 검토해서 위험 수준',
    'ReportProductionIssuesHelp'=>'이 보고서는 모든 열 위험으로 제출되 생산 문제의 주문에 의해 위험 수준',
    'ReportRiskScoringHelp'=>'이 보고서는 모든 위험을 득점 방법과 위험을 득점을 각각 사용하여',
    'ReportClosedHelp'=>'이 보고서는 모든 위험 폐쇄 주문에 의해 위험 수준',
    'ReportSubmittedByDateHelp'=>'이 보고서는 모든 위험에 의해 주문 제출한 날짜',
    'ReportMitigationsByDateHelp'=>'이 보고서에는 모든 완화 계획에 의해 주문을 완화 date',
    'ReportMgmtReviewsByDateHelp'=>'이 보고서에는 모든 관리 리뷰에 의해 주문을 검토 date',
    'ReportProjectsAndRisksHelp'=>'이 보고서는 모든 프로젝트 위험을 할당',
    'Language'=>'언어',
    'AllOpenRisksNeedingReview'=>'열려있는 모든 위험을 필요로 하는 검토',
    'ReportReviewNeededHelp'=>'이 보고서에는 모든 위험을 필요로 하는 관리 리뷰',
    'CustomValue'=>'사용자 정의 가치',
    'ClosedRisksByDate'=>'위험 폐쇄 날짜',
    'DateClosed'=>'날짜 폐쇄',
    'ClosedBy'=>'에 의해 폐쇄',
    'ReportClosedByDateHelp'=>'이 보고서는 모든 위험에 의해 주문감 날짜',
    'AllOpenRisksByTeam'=>'열려있는 모든 위험에 의해 팀',
    'ReportRiskTeamsHelp'=>'이 보고서에는 모든 팀과 위험을 각각에 할당',
    'Unassigned'=>'Unassigned',
    'AllOpenRisksByTechnology'=>'열려있는 모든 위험에 의해 기술',
    'ReportRiskTechnologiesHelp'=>'이 보고서에 모두 기술하고 위험에 할당된 각',
    'RiskLevel'=>'위험 수준',
    'BasedOnTheCurrentRiskScore'=>'기반으로 현재의 위험 점수,다음 검토될 것입 ',
    'WouldYouLikeToUseADifferentDate'=>'사용하시겠습니까 다른 날짜를 대신가?',
    'RisksOpenedAndClosedOverTime'=>'위험을 열고 닫을 시간',
    'AllRiskScoresAreAdjusted'=>'모든 위험 점수에 맞게 조정에 0-10 규모입니다.',
    'DetermineProjectStatus'=>'프로젝트 상태 확인',
    'ProjectStatusHelp'=>'프로젝트 장소로킷에 기초의 현재 상태를 확인할 수 있습니다.',
    'ActiveProjects'=>'Active 프로젝트',
    'OnHoldProjects'=>'에서 프로젝트 개최',
    'CompletedProjects'=>'완료 프로젝트',
    'CancelledProjects'=>'취소 프로젝트',
    'UpdateProjectStatuses'=>'프로젝트 업데이트 상태',
    'HighRiskReport'=>'높은 위험 보고서',
    'TotalOpenRisks'=>'총 위험 오픈',
    'TotalHighRisks'=>'총 높은 위험성',
    'HighRiskPercentage'=>'위험이 높은 비율',
    'UpdateClassicScore'=>'업데이트 클래식 점수',
    'CurrentLikelihood'=>'현재 가능성',
    'CurrentImpact'=>'현재의 영향',   
    'UpdateCVSSScore'=>'업데이트 CVSS 점수',
    'BaseScoreMetrics'=>'기본 점수 측정',
    'AttackVector'=>'공격',
    'AttackComplexity'=>'공격의 복잡성',
    'Authentication'=>'인증',
    'ConfidentialityImpact'=>'기밀성 영향',
    'IntegrityImpact'=>'Integrity 영향',
    'AvailabilityImpact'=>'가용성에 미치는 영향',
    'TemporalScoreMetrics'=>'시간 점수가 메트릭스',
    'Exploitability'=>'악용 가능성',
    'RemediationLevel'=>'재구성 수준',
    'ReportConfidence'=>'보고서 신뢰',
    'EnvironmentalScoreMetrics'=>'환경 지표수',
    'CollateralDamagePotential'=>'담보 손상 잠재력',
    'TargetDistribution'=>'대상 배포',
    'ConfidentialityRequirement'=>'비밀유지 요건',
    'IntegrityRequirement'=>'전성 요구 사항',
    'AvailabilityRequirement'=>'가용성 요구 사항',
    'UpdateDREADScore'=>'업데이트 공 점수',
    'DamagePotential'=>'손상 잠재력',
    'Reproducibility'=>'재현성',
    'AffectedUsers'=>'영향을 받는 사용자',
    'Discoverability'=>'검색',
    'UpdateOWASPScore'=>'업데이트 OWASP 점수',
    'ThreatAgentFactors'=>'위협 요인',
    'SkillLevel'=>'기술 수준',
    'Motive'=>'동기',
    'Opportunity'=>'회',
    'Size'=>'크기',
    'VulnerabilityFactors'=>'취약성 요소',
    'EaseOfDiscovery'=>'편의 검색',
    'EaseOfExploit'=>'의 편의성을 악용',
    'Awareness'=>'식',
    'IntrusionDetection'=>'침입 탐지',
    'TechnicalImpact'=>'기술 영향',
    'LossOfConfidentiality'=>'의 손실을 비밀',
    'LossOfIntegrity'=>'손실 무결성',
    'LossOfAvailability'=>'의 손실을 가용성',
    'LossOfAccountability'=>'의 손실 책임',
    'BusinessImpact'=>'비즈니스에 미치는 영향',
    'FinancialDamage'=>'손상 금융',
    'ReputationDamage'=>'평판을 손상',
    'NonCompliance'=>'비 준수',
    'PrivacyViolation'=>'개인 정보 보호 위반',
    'UpdateCustomScore'=>'업데이트 사용자 정의 점수',
    'ManuallyEnteredValue'=>'수동으로 입력 값',
    'ScoreByClassic'=>'점수에 의 클래식',
    'ScoreByCVSS'=>'점수에 의해 CVSS',
    'ScoreByDREAD'=>'점수에 의해 공포',
    'ScoreByOWASP'=>'점수에 의해 OWASP',
    'ScoreByCustom'=>'점수에 의해 사용자 지정',
    'BaseVector'=>'기 벡터',
    'TemporalVector'=>'시간 벡터',
    'EnvironmentalVector'=>'환경 벡터',
    'SupportingDocumentation'=>'지원 문서',
    'Import'=>'가져오기',
    'Export'=>'내보내기',
    'ActivateTheImportExportExtra'=>'활성화를 가져오기/내보내기 추가',
    'PrintableView'=>'인쇄 미리보기',
    'GroupBy'=>'그룹에 의해',
    'IncludedColumns'=>'포함된 열이',
    'AllRisks'=>'모든 위험',
    'DynamicRiskReport'=>'동적 위험 보고서',
    'ObsoleteReports'=>'폐기 보고서',
    'Project'=>'프로젝트',
    'SortBy'=>'으로 정렬',
    'MonthSubmitted'=>'달 제출',
    'AssetManagement'=>'자산 관리',
    'AutomatedDiscovery'=>'자동화된 검색',
    'BatchImport'=>'가',
    'ManageAssets'=>'자산 관리',
    'AssetValuation'=>'자산 가치 평가',
    'AllowAccessToAssetManagementMenu'=>'액세스할 수 있다"자산 관리"메뉴',
    'AutomatedDiscoveryHelp'=>'모든 라이브 IP 주소를 입력한 IP 범위에 있습니다. 라이브 IP 주소에 추가될 것입 자산 목록입니다. 허용 가능한 포맷:',
    'IPRange'=>'IP 범위',
    'Search'=>'검색',
    'AddANewAsset'=>'추가로 새로운 자산',
    'DeleteAnExistingAsset'=>'삭제하려면 기존의 자산',
    'AssetName'=>'자산 이름',
    'IPAddress'=>'IP 주소',
    'AssetWasAddedSuccessfully'=>'자산이 성공적으로 추가되었습니다.',
    'AssetWasDeletedSuccessfully'=>'자산이 성공적으로 삭제됩니다.',
    'ThereWasAProblemAddingTheAsset'=>'문제가 있었 추가하여 자산입니다.',
    'ThereWasAProblemDeletingTheAsset'=>'문제가 발생했을 삭제하의 자산입니다.',
    'ComingSoon'=>'곧',
    'ExportRisks'=>'수출 위험',
    'ExportMitigations'=>'내보내기 완화',
    'ExportReviews'=>'Export 리뷰',
    'ExportCombined'=>'수출 결합',
    'MitigatedBy'=>'여 완화',
    'MitigationId'=>'완화 ID',
    'ReviewId'=>'리뷰 ID',
    'AffectedAssets'=>'영향을 받는 자산',
    'Activate'=>'활성화',
    'DeleteRisks'=>'위험 삭제',
    'DeletedRisksCannotBeRecovered'=>'삭제 위험을 복구할 수 없습니다',
    'RisksDeletedSuccessfully'=>'위험(s)이 성공적으로 삭제',
    'ThereWasAProblemDeletingTheRisk'=>'문제가 있었 삭제 위험을(s)',
    'Activated'=>'활성화',
    'IWantToReviewInsignificantRiskEvery'=>'고 싶 리뷰 하찮은 위험 모',
    'Insignificant'=>'하찮',
    'IConsiderVeryHighRiskToBeAnythingGreaterThan'=>'을 고려가 매우 높은 위험을 아무것도 보다 큰',
    'IConsiderHighRiskToBeLessThanAboveButGreaterThan'=>'내 생각은 위험이 높은 것보다 위에 있지만,보다 큰',
    'VeryHigh'=>'매우 높',
    'VeryHighRisk'=>'매우 높은 위험',
    'IWantToReviewVeryHighRiskEvery'=> '고 싶을 검토하는 위험이 매우 높은 모든',
    'AbleToReviewVeryHighRisks'=>'을 검토할 수 있고 매우 높은 위험성',
    'AbleToReviewInsignificantRisks'=>'을 검토할 수 있는 하찮은 위험성',
    'TotalVeryHighRisks'=>'총 매우 높은 위험성',
    'VeryHighRiskPercentage'=>'위험이 매우 높은 백분율',
    'AllTeams'=>'모든 팀',
    'FileUploadSettings'=>'파일 업로드 설정',
    'AllowedFileTypes'=>'허용되는 파일 유형',
    'AddNewFileTypeOf'=>'추가로 새로운 파일의 형식',
    'DeleteCurrentFileTypeOf'=>'삭제재의 파일 형식',
    'MaximumUploadFileSize'=>'최대 업로드 파일의 크기',
    'Bytes'=>'바',
    'CheckAll'=>'모든 확인',
    'CheckAllRiskMgmt'=>'체크인 모든 위험 관리',
    'CheckAllAssetMgmt'=>'인 자산 관리',
    'CheckAllConfigure'=>'체크인 모든 구성',
    'MitigationTeam'=>'완화 팀',
    'ImportRisks'=>'가져오기 위험',
    'ImportAssets'=>'자산 가져오기',
    'AssetValue'=>'자산 가치',
    'Register'=>'등록',
    'RegisterSimpleRisk'=>'등록 SimpleRisk',
    'RegistrationText'=>'등록하여 SimpleRisk 를 제공할 것입니다 당신의 연락처 정보할 수 있도록 최신으로 업데이트한 정보를 공개하고 중요한 알림 보안합니다. 귀하의 정보는 절대 판매하는 제삼자. 등록된 경우도 할 수있는 능력을 가지고 백업하고 업그레이드 버튼을 클릭합니다.',
    'RegistrationInformation'=>'등록 정보',
    'Company'=>'회사',
    'JobTitle'=>'직위',
    'Phone'=>'전화',
    'UpgradeSimpleRisk'=>'업그레이드 SimpleRisk',
    'UpgradeInstructions'=>'이 섹션을 사용하여 업그레이드를 추가합니다. 지고 있는지 확인하는 최신 버전으로 선택하면"업데이트를"다시,등록 및 다운로드 페이지합니다.',
    'NoUpgradeNeeded'=>'업그레이드가 필요합니다.',
    'BackupDatabase'=>'백업 데이터베이스',
    'UpgradeApplication'=>'업그레이드 응용 프로그램',
    'UpgradeDatabase'=>'데이터베이스를 업그레이드',
    'CustomExtras'=>'사용자 정의 엑스트라',
    'CustomExtrasText'=>'응용 프로그램을 사용하면 모든 것을 무료로하고,오른쪽? 희망이 핵심 SimpleRisk 플랫폼 수 있는 모든 서비스를 제공하기 위해 귀하의 위험 관리가 필요합니다. 하지만,당신은 여전히 원하는 더 많은 기능을,우리는 우리의 시리즈를 개발했"Extras"그는 그렇게 할 것입니다.',
    'Upgrade'=>'업그레이드',
    'Install'=>'설치',
    'Purchase'=>'구매',
    'PasswordPolicy'=>'비밀번호 정책',
    'MinimumNumberOfCharacters'=>'최소 문자 수',
    'RequireAlphaCharacter'=>'필요한 알파 캐릭터',
    'RequireUpperCaseCharacter'=>'필요한 경우 상자',
    'RequireLowerCaseCharacter'=>'필요한 낮은 경우 캐릭터',
    'RequireNumericCharacter'=>'필요한 숫자',
    'RequireSpecialCharacter'=>'필요한 특별한 캐릭터',
    'Enabled'=>'사용 가능',
    'RiskPyramid'=>'위험을 피라미드',
    'RiskPyramidDescription'=>'위험을 피라미드 위에 표시하는 데 도움이 분포의 위험이 다양한 위험 수준입니다. 최고 무거운 피라미드는 로그인하실 수 있습 귀하의 조직은 너무 많은 위험이 있습니다.',
    'RiskAdvice'=>'위험 통보',
    'AddDeleteAssets'=>'추가 및 삭제 자산',
    'EditAssets'=>'자산 편집',
    'AutomaticAssetValuation'=>'자동산 평가',
    'ManualAssetValuation'=>'수동 자산 가치 평가',
    'MinimumValue'=>'최소값',
    'MaximumValue'=>'최대값',
    'ValueRange'=>'값의 범위',
    'DefaultAssetValuation'=>'기본자산 가치 평가',
    'Default'=>'기본',
    'RisksAndAssets'=>'위험과 자산',
    'Report'=>'보고',
    'RisksByAsset'=>'위험에 의해 자산',
    'AssetsByRisk'=>'자산에 의해 위험이',
    'MaximumQuantitativeLoss'=>'최대 정량적 손실',
    'MitigationOwner'=>'완화 소유자',
    'MitigationCost'=>'완화 비용',
    'RiskColumns'=>'위험 열',
    'MitigationColumns'=>'완화 열',
    'ReviewColumns'=>'열 검토',
    'ChangeStatus'=>'상태 변경',
    'SetRiskStatusTo'=>'트 위험 상태',
    'AddNewStatusNamed'=>'추가로 새로운 상태라는 이름',
    'DeleteStatusNamed'=>'삭제 상태라는 이름',
    'DefaultCurrencySymbol'=>'기본 통화 기호',
    'DefaultValues'=>'기본값',
    'RiskSource'=>'위험 원',
    'AddNewSourceNamed'=>'추가로 새로운 소스라는 이름',
    'DeleteSourceNamed'=>'삭제를 원본 이름',
    'CheckAllAssessments'=>'체크인 모든 평가',
    'AllowAccessToAssessmentsMenu'=>'한 액세스를 허용하"평가"메뉴',
    'Assessments'=>'평가',
    'AvailableAssessments'=>'사용할 수 있는 평가',
    'PendingRisks'=>'보류 중인 위험',
    'CreateAssessment'=>'성 평가',
    'EditAssessment'=>'편집 평가',
    'Overview'=>'개요',
    'OpenVsClosed'=>'오픈 대 폐쇄',
    'MitigatedVsUnmitigated'=>'완화 vs 완화할 수 없',
    'ReviewedVsUnreviewed'=>'검 대 검토되지 않은',
    'OpenedRisks'=>'열 위험',
    'MailSettings'=>'메일 설정',
    'TransportAgent'=>'전송 에이전트',
    'FromName'=>'에서 이름',
    'FromEmail'=>'이메일',
    'ReplyToName'=>'ReplyTo 이름',
    'ReplyToEmail'=>'ReplyTo 이메일',
    'Host'=>'Host',
    'SMTPAuthentication'=>'SMTP 인증',
    'Encryption'=>'암호화',
    'Port'=>'트',
    'Next'=>'다음',
    'NewAssessmentQuestion'=>'새로운 질문성 평가',
    'Question'=>'질문',
    'RiskScore'=>'위험 점수',
    'SubmitRisk'=>'출 위험',
    'Answer'=>'응답',
    'AddQuestion'=>'추가 질문',
    'SaveAssessment'=>'저장 평가',
    'SendAssessment'=>'보 평가',
    'DeleteAssessment'=>'를 삭제합성 평가',
    'AssessmentName'=>'평가 이름',
    'SendTo'=>'보내기',
    'ActiveAssessments'=>'Active 평가',
    'SentTo'=>'송',
    'From'=>'서',
    'Key'=>'키',
    'GoToSSOLoginPage'=>'SSO 로그인 페이지로 이동',
    'APIKey'=>'API 키',
    'GenerateAPIKey'=>'API 키를 생성',
    'RotateAPIKey'=>'회전 API 키',
    'InvalidateAPIKey'=>'API 키를 무효화',
    'Deactivate'=>'비활성화',
    'ImportExportExtra'=>'Import-Export Extra',
    'SaveDetails'=>'정보 저장',
    'ClearForm'=>'클리어 폼',
    'SaveMitigation'=>'저장 완화',
    'Cancel'=>'취소하다',
    'SubmitReview'=>'검토 제출',
    'UnassignedRisks'=>'할당되지 않은 위험',
    'DisableRegistrationNotice'=>'안 등록주의 사항',
    ''=>'',
);

?>