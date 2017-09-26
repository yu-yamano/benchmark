package main

import (
	"fmt"
	"os"
	"time"
	"io"
	"encoding/csv"
	"log"
	"sync"
	"strconv"
)

func main() {
	start := time.Now();
	fmt.Printf("Start!\n")
	var wg sync.WaitGroup
	c := make(chan int, 20) // 最大並列数
	for i:=0; i<20; i++{
		wg.Add(1)
		go func(num int){
			c<-1
			defer func(){
				<-c
				wg.Done()
			}()
			process(num, "/Users/usr0301564/gmo-am/access_log_ad/access_log.20170915_09_ad01", "/Users/usr0301564/gmo-am/go/src/base/")
		}(i)
	}
	wg.Wait()
	end := time.Now();
	fmt.Printf("%f秒\n", (end.Sub(start)).Seconds())
	fmt.Printf("Done!\n")
}

func process(num int, logFile string, csvFile string) {
	fmt.Printf("%d 番目の処理開始\n", num)

	// int -> string
	csvFile +=  "test" + strconv.Itoa(num) + ".csv"

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
		// csv write
		writer.Write(new_record)
	}
	writer.Flush()
	fmt.Printf("%d 番目の処理終了\n", num)
}

func failOnError(err error) {
	if err != nil {
		log.Fatal("Error:", err)
	}
}
