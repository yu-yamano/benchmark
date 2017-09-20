package main

import (
	"fmt"
	"os"
	"time"
	"io"
	"encoding/csv"
	"log"
)

func main() {
	start := time.Now();
	createCsv("/Users/usr0301564/gmo-am/access_log_ad/access_log.20170915_09_ad01", "/Users/usr0301564/gmo-am/go/src/base/test.csv")
	end := time.Now();
	fmt.Printf("%f秒\n",(end.Sub(start)).Seconds())
}

func createCsv(logFile string, csvFile string) {
	// read accesslog
	accesslog, err := os.Open(logFile)
	if err != nil {
		fmt.Fprintf(os.Stderr, "File %s could not read: %v\n", logFile, err)
		os.Exit(1)
	}
	defer accesslog.Close()

	// edit csv
	csvfile, err := os.Create(csvFile)
	if err != nil {
		fmt.Fprintf(os.Stderr, "File %s could not read: %v\n", csvfile, err)
		os.Exit(1)
	}
	defer csvfile.Close()

	reader := csv.NewReader(accesslog) //utf8
	reader.Comma = ' '
	reader.LazyQuotes = true
	reader.FieldsPerRecord = -1

	writer := csv.NewWriter(csvfile) //utf8
	writer.UseCRLF = true

	log.Printf("Start")
	for {
		record, err := reader.Read()
		if err == io.EOF {
			break
		} else {
			failOnError(err)
		}
		first := record[0]
		last := record[len(record)-1]
		record[0] = last
		record[len(record)-1] = first

		var new_record []string
		for i, v := range record {
			if i >= 0 {
				new_record = append(new_record, v)
			}
		}
		writer.Write(new_record)
	}
	writer.Flush()
	log.Printf("Finish !")
}

func failOnError(err error) {
	if err != nil {
		log.Fatal("Error:", err)
	}
}