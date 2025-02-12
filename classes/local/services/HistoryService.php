<?php

namespace local_certificate_management\local\services;

use RuntimeException;
use local_certificate_management\local\repositories\UsersRepository;
use local_certificate_management\local\repositories\CertificateRepository;

class HistoryService
{
    private static ?HistoryService $service = null;

    private CertificateRepository $certificateRepository;
    private UsersRepository $usersRepository;

    public function __construct()
    {
        $this->certificateRepository = new CertificateRepository();
        $this->usersRepository = new UsersRepository();
    }

    public function issueHistory(
        int $courseId,
        int $userId
    ): array
    {
        $certificate = $this->certificateRepository->findByUserIdAndCourse($userId, $courseId);

        if (empty($certificate)) {
            throw new RuntimeException();
        }

        $pdfService = PdfService::getService();

        $pdfService->deleteFile($certificate->id);

        $historyUrl = $pdfService->generateGradePdf(
            $userId,
            $courseId,
            $certificate->id
        );

        return [
            'history' => $historyUrl
        ];
    }

    public function getHistoryUrl(
        int $courseId,
        int $userId
    ): array
    {
        $certificate = $this->certificateRepository->findByUserIdAndCourse($userId, $courseId);

        if (empty($certificate)) {
            throw new RuntimeException();
        }

        $user = $this->usersRepository->getUser($userId);
        $fullname = $user->firstname . ' ' . $user->lastname;

        $historyUrl = PdfService::getService()->getHistoryGradeUrl(
            $certificate->id,
            $fullname
        );

        return [
            'history' => $historyUrl
        ];
    }

    public static function getService(): HistoryService
    {
        if (self::$service === null) {
            self::$service = new self();
        }

        return self::$service;
    }
}