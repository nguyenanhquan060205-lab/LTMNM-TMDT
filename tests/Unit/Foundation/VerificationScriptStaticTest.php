<?php

namespace Tests\Unit\Foundation;

use Tests\TestCase;

class VerificationScriptStaticTest extends TestCase
{
    public function test_verification_scripts_have_safe_database_and_commit_guards(): void
    {
        $ps1 = file_get_contents(base_path('scripts/verify-parallel-readiness.ps1'));
        $sh = file_get_contents(base_path('scripts/verify-parallel-readiness.sh'));

        $this->assertIsString($ps1);
        $this->assertIsString($sh);
        $this->assertStringNotContainsString('parse_ini_file', $ps1.$sh);
        $this->assertStringContainsString('DB_CONNECTION', $ps1);
        $this->assertStringContainsString('DB_DATABASE', $ps1);
        $this->assertStringContainsString("StartsWith('techsecond_test')", $ps1);
        $this->assertStringContainsString('ExpectedCommit mismatch', $ps1);
        $this->assertStringContainsString('techsecond_test*', $sh);
        $this->assertStringContainsString('ExpectedCommit mismatch', $sh);
        $this->assertStringContainsString('final_status', $ps1.$sh);
    }

    public function test_verification_scripts_do_not_collect_secret_fields(): void
    {
        $scripts = file_get_contents(base_path('scripts/verify-parallel-readiness.ps1'))
            .file_get_contents(base_path('scripts/verify-parallel-readiness.sh'));

        $this->assertStringNotContainsString('DB_PASSWORD', $scripts);
        $this->assertStringNotContainsString('APP_KEY', $scripts);
    }

    public function test_codeowners_template_and_generators_are_placeholder_safe(): void
    {
        $template = file_get_contents(base_path('.github/CODEOWNERS.template'));
        $ps1 = file_get_contents(base_path('scripts/generate-codeowners.ps1'));
        $sh = file_get_contents(base_path('scripts/generate-codeowners.sh'));

        foreach (['@TV1_USERNAME', '@TV2_USERNAME', '@TV3_USERNAME', '@TV4_USERNAME', '@TV5_USERNAME'] as $placeholder) {
            $this->assertStringContainsString($placeholder, $template);
        }

        $this->assertStringContainsString('Refusing to write CODEOWNERS while placeholders remain', $ps1);
        $this->assertStringContainsString('Refusing to write CODEOWNERS while placeholders remain', $sh);
    }
}
