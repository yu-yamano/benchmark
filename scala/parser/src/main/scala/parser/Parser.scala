package parser

import java.util

object Parse {

  import java.io.FileReader
  import java.io.FileWriter
  import com.orangesignal.csv.{CsvWriter, CsvReader, QuotePolicy, CsvConfig}
  import scala.collection.JavaConversions._
  //  import java.io._
  //  import com.opencsv.CSVReader
  //  import com.opencsv.CSVWriter
  //  import com.github.tototoshi.csv._
  //  import org.supercsv.io.CsvListReader
  //  import org.supercsv.prefs.CsvPreference
  //  import java.io.FileReader
  //  import java.nio.charset.StandardCharsets

  def main(args: Array[String]): Unit = {
    val start = System.currentTimeMillis
    //    val accesslogfile = "/Users/usr0301564/gmo-am/access_log_ad/access_log.20170915_09_ad01"
    val accesslogfile = "/Users/usr0301564/gmo-am/access_log_ad/access_log.20170915_09_ad01_comma"
    val csvfile = "/Users/usr0301564/gmo-am/benchmark/scala/test.csv"
    createCsv(accesslogfile, csvfile)
    val end = System.currentTimeMillis
    val interval = end - start
    println((interval) + "ミリ秒")
  }

  def swapList(r: util.List[String]) = ???

  def createCsv(accesslogfile: String, csvfile: String): Unit = {

    // orangesignal.csv
    val readCfg = new CsvConfig(',', '\"', '\"')
    readCfg.setVariableColumns(true)
    //        val reader = new CsvReader(new FileReader(accesslogfile), readCfg)
    val reader = new CsvReader(new FileReader(accesslogfile))
    val writeCfg = new CsvConfig(' ', '\"', '\"')
    writeCfg.setQuotePolicy(QuotePolicy.MINIMAL)
    writeCfg.setVariableColumns(true)
    val writer = new CsvWriter(new FileWriter(csvfile))
    Iterator.continually(reader.readValues).takeWhile(_ != null).foreach { r =>
      writer.writeValues(swapList(r.toList)(0, 10))
    }
  }

  def swapArray[T](arr: Array[T])(i: Int, j: Int): Array[T] = {
    val first = arr(i)
    val last = arr(j)
    arr(i) = last
    arr(j) = first
    return arr
  }

  def swapList(list: List[String])(i: Int, j: Int): List[String] = {

    // TODO Listを入れ替える
    return list.toList
  }
}