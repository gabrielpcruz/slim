<?php

use App\Service\Directory\Directory;
use function PHPUnit\Framework\assertEquals;

$rootPath = getenv("GITHUB_WORKSPACE") ? getenv("GITHUB_WORKSPACE") : ROOT_PATH;

test('must correctly turn an directory into array', function () use ($rootPath) {

    $tested = Directory::turnNameSpacePathIntoArray(
        "{$rootPath}/tests/Service/Directory/DirectoryTest",
        "\\Tests\\Service\\Directory\\DirectoryTest\\"
    );

    $differences = array_diff($tested, [
        "\Tests\Service\Directory\DirectoryTest\FileOne",
        "\Tests\Service\Directory\DirectoryTest\FileTwo",
        "\Tests\Service\Directory\DirectoryTest\SubDirectoryTest\FileThree",
        "\Tests\Service\Directory\DirectoryTest\SubDirectoryTest\SubSubDirectoryTest\FileFour",
        "\Tests\Service\Directory\DirectoryTest\SubDirectoryTest\SubSubDirectoryTest\FileFour",
        "\Tests\Service\Directory\DirectoryTest\SubDirectoryTest\SubSubDirectoryTest\FileFive",
        "\Tests\Service\Directory\DirectoryTest\SubDirectoryTest\SubSubDirectoryTest\FileSix",
    ]);

    assertEquals(0, count($differences));
});
