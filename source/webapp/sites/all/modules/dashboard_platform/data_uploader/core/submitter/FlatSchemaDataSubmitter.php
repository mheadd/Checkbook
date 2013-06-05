<?php
/**
* This file is part of the Checkbook NYC financial transparency software.
* 
* Copyright (C) 2012, 2013 New York City
* 
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as
* published by the Free Software Foundation, either version 3 of the
* License, or (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU Affero General Public License for more details.
* 
* You should have received a copy of the GNU Affero General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


class FlatSchemaDataSubmitter extends AbstractControllerDataSubmitter {

    protected function truncateStorage() {
        $dataStructureController = data_controller_ddl_get_instance();

        $dataStructureController->truncateDatasetStorage($this->datasetName);
    }

    protected function submitRecordBatch(RecordMetaData $recordMetaData) {
        $dataManipulationController = data_controller_dml_get_instance();

        if ($recordMetaData->findKeyColumns() == NULL) {
            $this->insertedRecordCount += $dataManipulationController->insertDatasetRecordBatch($this->datasetName, $this->recordsHolder);
        }
        else {
            // even if we truncate the dataset we still need to support several instances of the same record
            list($insertedRecordCount, $updatedRecordCount, $deletedRecordCount) =
                $dataManipulationController->insertOrUpdateOrDeleteDatasetRecordBatch($this->datasetName, $this->recordsHolder);
            $this->insertedRecordCount += $insertedRecordCount;
            $this->updatedRecordCount += $updatedRecordCount;
            $this->deletedRecordCount += $deletedRecordCount;
        }
    }
}