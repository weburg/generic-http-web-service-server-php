<?php
namespace weburg\ghowst;

use weburg\ghowst\attributes\localdatetime;
use weburg\ghowst\attributes\offsetdatetime;

final class JsonNormalizer {
    public static function normalize(mixed $value): mixed {
        // NOTE: attribute-aware logic happens in normalizeObject()
        if ($value instanceof \DateTimeInterface) {
            return self::serializeOffsetDateTime($value); // default
        }

        if ($value instanceof \CURLFile) {
            return self::serializeCurlFile($value);
        }

        if (is_array($value)) {
            return array_map(
                static fn (mixed $item): mixed => self::normalize($item),
                $value
            );
        }

        if (is_object($value)) {
            return self::normalizeObject($value);
        }

        return $value;
    }

    private static function normalizeObject(object $object): array {
        $ref = new \ReflectionObject($object);
        $data = [];
        $classes = [];

        for ($class = $ref; $class !== false; $class = $class->getParentClass()) {
            $classes[] = $class;
        }

        foreach (array_reverse($classes) as $class) {
            foreach ($class->getProperties() as $prop) {
                if (!$prop->isPublic() || $prop->getDeclaringClass()->getName() !== $class->getName()) {
                    continue;
                }

                $name = $prop->getName();
                $value = $prop->isInitialized($object) ? $prop->getValue($object) : null;

                // --- Attribute-aware datetime handling ---
                if ($value instanceof \DateTimeInterface) {
                    $attrs = $prop->getAttributes();

                    foreach ($attrs as $attr) {
                        $attrName = $attr->getName();

                        if ($attrName === localdatetime::class) {
                            $data[$name] = self::serializeLocalDateTime($value);
                            continue 2;
                        }

                        if ($attrName === offsetdatetime::class) {
                            $data[$name] = self::serializeOffsetDateTime($value);
                            continue 2;
                        }
                    }

                    // No attribute → default to offsetdatetime
                    $data[$name] = self::serializeOffsetDateTime($value);
                    continue;
                }
                // -------------------------------------------------------------

                $data[$name] = self::normalize($value);
            }
        }

        return $data;
    }

    // Legacy fallback — only used when no attribute is present
    private static function serializeDateTime(\DateTimeInterface $dateTime): string {
        $offset = $dateTime->getOffset();

        if ($offset !== 0) {
            return $dateTime->format('Y-m-d\TH:i:s.uP');
        }

        $tz = $dateTime->getTimezone()->getName();
        if ($tz === 'UTC' || $tz === 'Z' || $tz === '+00:00') {
            return $dateTime->format('Y-m-d\TH:i:s.u');
        }

        return $dateTime->format('Y-m-d\TH:i:s.u');
    }

    private static function serializeLocalDateTime(\DateTimeInterface $localDateTime): string {
        return $localDateTime->format('Y-m-d\TH:i:s.u');
    }

    private static function serializeOffsetDateTime(\DateTimeInterface $offsetDateTime): string {
        return $offsetDateTime->format('Y-m-d\TH:i:s.uP');
    }

    private static function serializeCurlFile(\CURLFile $file): array {
        $filename = $file->getFilename();

        return basename($filename);
    }
}
?>