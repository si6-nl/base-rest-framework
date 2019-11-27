<?php

namespace Si6\Base\Utils;

use Illuminate\Support\Facades\Storage;
use League\Csv\CannotInsertRecord;
use League\Csv\CharsetConverter;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\Writer;
use Si6\Base\Exceptions\UploadExportFail;

class CsvUtil
{
    /**
     * @return Writer
     * @throws Exception
     */
    protected function prepareWriter()
    {
        $writer = Writer::createFromStream(fopen('php://temp', 'r+'));
        $writer->setOutputBOM(Reader::BOM_UTF16_LE);
        $writer->setDelimiter("\t");
        CharsetConverter::addTo($writer, 'UTF-8', 'UTF-16LE');

        return $writer;
    }

    /**
     * @param $data
     * @param array $headers
     * @return string
     * @throws Exception
     * @throws CannotInsertRecord
     */
    protected function push($data, $headers = [])
    {
        $writer = $this->prepareWriter();

        $writer->insertOne($headers);

        $writer->insertAll($data);

        return $writer->getContent();
    }

    /**
     * @param $data
     * @param $path
     * @param $headers
     * @return mixed
     * @throws CannotInsertRecord
     * @throws Exception
     * @throws UploadExportFail
     */
    public function export($data, $path, $headers)
    {
        $content = $this->push($data, $headers);
        $upload  = Storage::put($path, $content, [
            'ContentType'        => 'text/csv; charset=UTF-8',
            'ContentDisposition' => 'attachment',
        ]);

        if (!$upload) {
            throw new UploadExportFail();
        }

        return $path;
    }
}
